<?php

namespace App\Http\Controllers;


use App\Models\Stock;
use App\Models\MotorUnit;
use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\facades\Log;
use App\Models\PurchaseOrdersDetails;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sortBy', 'order');
        $order = $request->get('order', 'asc');
        $search = $request->get('search');

        $purchaseOrders = PurchaseOrder::with('vendor')
            ->when($search, function ($query, $search) {
                $query->where('order', 'like', '%' . $search . '%')
                    ->orWhere('invoice', 'like', '%' . $search . '%')
                    ->orWhereHas('vendor', function ($q) use ($search) {
                        $q->where('nama_Vendor', 'like', '%' . $search . '%');
                    })
                    ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->orderBy($sortBy, $order)
            ->paginate(10);

        return view('layouts.purchase_orders.index', compact('purchaseOrders', 'sortBy', 'order', 'search'));
    }

    public function create()
    {
        $vendors = Distributor::all();
        return view('layouts.purchase_orders.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:distributors,id',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $newInvoice = time();

        $latestOrder = PurchaseOrder::max('order');
        $newOrder = $latestOrder ? (int)$latestOrder + 1 : 1;

        PurchaseOrder::create([
            'invoice' => $newInvoice,
            'vendor_id' => $request->vendor_id,
            'status' => $request->status,
            'order' => $newOrder,
        ]);

        return redirect()->route('purchase_orders.index')->with('message', 'Purchase Order created successfully.');
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id); // Ensure it retrieves a valid record
        $vendors = Distributor::all();
        return view('layouts.purchase_orders.edit', compact('purchaseOrder', 'vendors'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'vendor_id' => 'required|exists:distributors,id',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        Log::info("Updating PO: {$purchaseOrder->id}, Old status: {$purchaseOrder->status}, New status: {$request->status}");

        try {
            DB::beginTransaction();

            $oldStatus = $purchaseOrder->status;
            $newStatus = $request->status;

            $updated = $purchaseOrder->update([
                'vendor_id' => $request->vendor_id,
                'status' => $newStatus,
            ]);

            if (!$updated) {
                throw new \Exception("Failed to update Purchase Order");
            }

            // Update status di PurchaseOrdersDetails
            $detailsUpdated = PurchaseOrdersDetails::where('invoice', $purchaseOrder->invoice)
                ->update(['status' => $newStatus]);

            Log::info("Updated {$detailsUpdated} PurchaseOrdersDetails records");

            // Jika status berubah menjadi completed, kirim data ke StockController
            if ($oldStatus != 'completed' && $newStatus == 'completed') {
                Log::info("Calling addToStock for invoice: {$purchaseOrder->invoice}");
                try {
                    $stockController = new StockController();
                    $stockController->addToStock($purchaseOrder->invoice);
                } catch (\Exception $e) {
                    Log::error("Error in addToStock: " . $e->getMessage());
                    throw $e; // Re-throw the exception to rollback the transaction
                }
            }

            DB::commit();

            Log::info("PO update completed successfully");

            return redirect()->route('purchase_orders.index')->with('message', 'Purchase Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating PO: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update Purchase Order: ' . $e->getMessage());
        }
    }

    public function getDetails($id)
    {
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            return response()->json([
                'success' => true,
                'vendor_name' => $purchaseOrder->distributor->nama_distributor ?? 'N/A',
                'status' => $purchaseOrder->status
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching purchase order details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail Purchase Order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::findOrFail($id);

            if ($purchaseOrder->status == 'completed') {
                return response()->json(['message' => 'Completed orders cannot be cancelled.'], 400);
            }

            if ($purchaseOrder->status == 'cancelled') {
                return response()->json(['message' => 'This order is already cancelled.'], 400);
            }

            $purchaseOrder->update(['status' => 'cancelled']);

            PurchaseOrdersDetails::where('invoice', $purchaseOrder->invoice)
                ->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json(['message' => 'Purchase Order cancelled successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to cancel Purchase Order: ' . $e->getMessage()], 500);
        }
    }

    public function complete($id)
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::findOrFail($id);

            if ($purchaseOrder->status == 'completed') {
                return response()->json(['message' => 'This order is already completed.'], 400);
            }

            if ($purchaseOrder->status == 'cancelled') {
                return response()->json(['message' => 'Cancelled orders cannot be completed.'], 400);
            }

            $purchaseOrder->update(['status' => 'completed']);

            PurchaseOrdersDetails::where('invoice', $purchaseOrder->invoice)
                ->update(['status' => 'completed']);

            // Call StockController to add items to stock
            $stockController = new StockController();
            $stockController->addToStock($purchaseOrder->invoice);

            DB::commit();

            return response()->json(['message' => 'Purchase Order completed successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to complete Purchase Order: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::findOrFail($id);

            $deletedOrder = $purchaseOrder->order;

            // Delete associated PurchaseOrdersDetails
            PurchaseOrdersDetails::where('invoice', $purchaseOrder->invoice)->delete();

            $purchaseOrder->delete();

            PurchaseOrder::where('order', '>', $deletedOrder)
                ->decrement('order');

            DB::commit();

            return redirect()->route('purchase_orders.index')->with('message', 'Purchase Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase_orders.index')->with('error', 'Gagal menghapus Purchase Order: ' . $e->getMessage());
        }
    }
}
