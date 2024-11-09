<?php

namespace App\Http\Controllers;

use App\Models\MasterMotor;
use App\Models\MasterWarna;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\MasterSparePart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $motors = MasterMotor::all();
        $spareParts = MasterSparePart::all();
        $masterWarna = MasterWarna::all();

        return view('layouts.purchase_orders_details.create', compact('purchaseOrders', 'motors', 'spareParts', 'masterWarna'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Validasi request
            $validatedData = $request->validate([
                'purchase_order_id' => 'required|exists:purchase_orders,id',

                'motor_ids' => 'nullable|array',
                'motor_ids.*' => 'exists:master_motors,id',

                'motor_quantities' => [
                    'nullable',
                    'array',
                    $request->has('motor_ids') ? 'size:' . count($request->motor_ids) : '',
                ],
                'motor_quantities.*' => 'integer|min:1',

                'motor_prices' => [
                    'nullable',
                    'array',
                    $request->has('motor_ids') ? 'size:' . count($request->motor_ids) : '',
                ],
                'motor_prices.*' => 'numeric|min:0',

                'motor_warna_ids' => [
                    'nullable',
                    'array',
                    $request->has('motor_ids') ? 'size:' . count($request->motor_ids) : '',
                ],
                'motor_warna_ids.*' => 'exists:master_warnas,id',

                'spare_part_ids' => 'nullable|array',
                'spare_part_ids.*' => 'exists:master_spare_parts,id',

                'spare_part_quantities' => [
                    'nullable',
                    'array',
                    $request->has('spare_part_ids') ? 'size:' . count($request->spare_part_ids) : '',
                ],
                'spare_part_quantities.*' => 'integer|min:1',

                'spare_part_prices' => [
                    'nullable',
                    'array',
                    $request->has('spare_part_ids') ? 'size:' . count($request->spare_part_ids) : '',
                ],
                'spare_part_prices.*' => 'numeric|min:0',
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
                    'redirect' => route('purchase_orders_details.index')
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
                    'status' => $purchaseOrder->status,
                    'warna_id' => $request->motor_warna_ids[$index],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        return $motorDetails;
    }

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
                    'status' => $purchaseOrder->status,
                    'warna_id' => null,
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
        $invoice = $detail->invoice;

        // Update semua detail dengan invoice yang sama
        PurchaseOrdersDetails::where('invoice', $invoice)->update(['status' => 'cancelled']);

        return redirect()->route('purchase_orders_details.index')->with('success', 'All order details with invoice ' . $invoice . ' have been canceled.');
    }

    public function show($id)
    {
        $purchaseOrderDetail = PurchaseOrdersDetails::findOrFail($id);
        $invoice = $purchaseOrderDetail->invoice;

        $purchaseOrderDetails = PurchaseOrdersDetails::with(['motor', 'sparePart', 'warna'])
            ->where('invoice', $invoice)
            ->get();

        if ($purchaseOrderDetails->isEmpty()) {
            return view('layouts.purchase_orders_details.show', ['message' => 'Tidak ada data']);
        } else {
            $motorDetails = $this->consolidateMotorDetails($purchaseOrderDetails->whereNotNull('motor_id'));
            $sparePartDetails = $this->consolidateDetails($purchaseOrderDetails->whereNotNull('spare_part_id'), 'sparePart');

            $totalPrice = $purchaseOrderDetails->sum('total_harga');

            return view('layouts.purchase_orders_details.show', [
                'invoice' => $invoice,
                'motorDetails' => $motorDetails,
                'sparePartDetails' => $sparePartDetails,
                'totalPrice' => $totalPrice
            ]);
        }
    }

    private function consolidateMotorDetails($details)
    {
        return $details->groupBy(function ($item) {
            return $item->motor->nama_motor . '-' . ($item->warna->nama_warna ?? 'No Color');
        })
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'name' => $firstItem->motor->nama_motor,
                    'color' => $firstItem->warna->nama_warna ?? 'No Color', // Ubah 'warna' menjadi 'color'
                    'quantity' => $group->sum('jumlah'),
                    'price' => $firstItem->harga,
                    'total_price' => $group->sum('total_harga'),
                ];
            })
            ->values();
    }

    private function consolidateDetails($details, $relation)
    {
        return $details->groupBy(function ($item) use ($relation) {
            return $item->$relation->nama_spare_part;
        })
            ->map(function ($group) use ($relation) {
                $firstItem = $group->first();
                return [
                    'name' => $firstItem->$relation->nama_spare_part,
                    'quantity' => $group->sum('jumlah'),
                    'price' => $firstItem->harga,
                    'total_price' => $group->sum('total_harga'),
                ];
            })
            ->values();
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
