<?php

namespace App\Http\Controllers;

use Log;
use App\Models\MasterMotor;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\MasterSparePart;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrdersDetails;

class PurchaseOrdersDetailsController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sortBy', 'invoice');
        $order = $request->get('order', 'asc');
        $search = $request->get('search');

        // Modified query to properly handle grouping
        $query = PurchaseOrdersDetails::select(
            'purchase_orders_details.invoice',
            'purchase_orders_details.status',
            'purchase_orders_details.order',
            DB::raw('MAX(purchase_orders_details.id) as id'),
            DB::raw('MAX(purchase_orders_details.created_at) as created_at')
        )
            ->groupBy('purchase_orders_details.invoice', 'purchase_orders_details.status', 'purchase_orders_details.order');
        // Apply search filter if provided
        if ($search) {
            $query->where('invoice', 'like', "%{$search}%");
        }

        // Apply sorting
        $purchaseOrdersDetails = $query->orderBy($sortBy, $order)
            ->paginate(10);

        return view(
            'layouts.purchase_orders_details.index',
            compact('purchaseOrdersDetails', 'sortBy', 'order', 'search')
        );
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::all();
        $motors = MasterMotor::select('id', 'nama_motor')->get();
        $spareParts = MasterSparePart::select('id', 'nama_spare_part')->get();

        return view('layouts.purchase_orders_details.create', compact('purchaseOrders', 'motors', 'spareParts'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // 1. Validasi request
            $validatedData = $request->validate([
                'purchase_order_id' => 'required|exists:purchase_orders,id',
                'motor_ids' => 'nullable|array',
                'motor_quantities' => 'nullable|array',
                'motor_prices' => 'nullable|array',
                'spare_part_ids' => 'nullable|array',
                'spare_part_quantities' => 'nullable|array',
                'spare_part_prices' => 'nullable|array',
            ]);
    
            // 2. Ambil data purchase order
            $purchaseOrder = PurchaseOrder::findOrFail($request->purchase_order_id);
    
            // Cek apakah invoice sudah ada, dan ambil nomor order yang ada atau tambahkan yang baru
            $existingOrder = PurchaseOrdersDetails::where('invoice', $purchaseOrder->invoice)->value('order');
            $newOrder = $existingOrder ?? (PurchaseOrdersDetails::max('order') + 1);
    
            // 3. Proses data motor
            $purchaseOrderDetails = $this->processMotorDetails($request, $purchaseOrder, $newOrder);
    
            // 4. Proses data spare part
            $purchaseOrderDetails = array_merge(
                $purchaseOrderDetails,
                $this->processSparePartDetails($request, $purchaseOrder, $newOrder)
            );
    
            // 5. Simpan ke database
            if (!empty($purchaseOrderDetails)) {
                PurchaseOrdersDetails::insert($purchaseOrderDetails);
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase order details created successfully.',
                    'redirect' => route('purchase_orders_details.index') // Tambahkan URL redirect
                ]);
            }
    
            throw new \Exception('No data to save');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving purchase order details: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Error saving purchase order details: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Process motor details.
     */
    protected function processMotorDetails($request, $purchaseOrder, $newOrder)
    {
        $motorDetails = [];
    
        if ($request->has('motor_ids')) {
            foreach ($request->motor_ids as $index => $motorId) {
                $motorDetails[] = [
                    'purchase_order_id' => $purchaseOrder->id,
                    'invoice' => $purchaseOrder->invoice,
                    'motor_id' => $motorId,
                    'spare_part_id' => null,
                    'jumlah' => $request->motor_quantities[$index],
                    'harga' => $request->motor_prices[$index],
                    'total_harga' => $request->motor_quantities[$index] * $request->motor_prices[$index],
                    'order' => $newOrder,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
    
        return $motorDetails;
    }
    
    /**
     * Process spare part details.
     */
    protected function processSparePartDetails($request, $purchaseOrder, $newOrder)
    {
        $sparePartDetails = [];
    
        if ($request->has('spare_part_ids')) {
            foreach ($request->spare_part_ids as $index => $sparePartId) {
                $sparePartDetails[] = [
                    'purchase_order_id' => $purchaseOrder->id,
                    'invoice' => $purchaseOrder->invoice,
                    'motor_id' => null,
                    'spare_part_id' => $sparePartId,
                    'jumlah' => $request->spare_part_quantities[$index],
                    'harga' => $request->spare_part_prices[$index],
                    'total_harga' => $request->spare_part_quantities[$index] * $request->spare_part_prices[$index],
                    'order' => $newOrder,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
    
        return $sparePartDetails;
    }
    

    public function cancel($id)
    {
        $detail = PurchaseOrdersDetails::findOrFail($id);
        $detail->status = 'cancelled';
        $detail->save();

        return redirect()->route('purchase_orders_details.index')->with('success', 'Order detail canceled.');
    }

    public function show($invoice)
    {
        $purchaseOrderDetails = PurchaseOrdersDetails::with(['motor', 'sparePart'])
            ->where('invoice', $invoice)
            ->get();
    
        if ($purchaseOrderDetails->isEmpty()) {
            return view('layouts.purchase_orders_details.show', ['message' => 'Tidak ada data']);
        } else {
            return view('layouts.purchase_orders_details.show', ['purchaseOrderDetails' => $purchaseOrderDetails]);
        }
    }
    

    public function destroy($id)
    {
        $purchaseOrderDetail = PurchaseOrdersDetails::findOrFail($id);
        $deletedOrder = $purchaseOrderDetail->order;

        $purchaseOrderDetail->delete();

        // Logika pengurutan jika dibutuhkan
        PurchaseOrdersDetails::where('order', '>', $deletedOrder)->decrement('order');

        return redirect()->route('purchase_orders_details.index')->with('message', 'Purchase Order Detail deleted successfully.');
    }
}
