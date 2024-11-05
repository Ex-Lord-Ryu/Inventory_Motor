<?php

namespace App\Http\Controllers;

use App\Models\MasterMotor;
use App\Models\MasterSparePart;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersDetails;

class PurchaseOrdersDetailsController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id');
        $order = $request->get('order', 'asc');
        $search = $request->get('search');

        $purchaseOrdersDetails = PurchaseOrdersDetails::with(['purchaseOrder', 'motor', 'sparePart'])
            ->when($search, function ($query) use ($search) {
                $query->where('order', 'like', "%{$search}%")
                      ->orWhere('invoice', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $order)
            ->paginate(10);

        return view('layouts.purchase_orders_details.index', compact('purchaseOrdersDetails', 'sortBy', 'order', 'search'));
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::all();
        // Get motors with their prices
        $motors = MasterMotor::select('id', 'nama_motor')->get();
        // Get spare parts with their prices
        $spareParts = MasterSparePart::select('id', 'nama_spare_part')->get();

        return view('layouts.purchase_orders_details.create', compact('purchaseOrders', 'motors', 'spareParts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'invoice' => 'required|string|unique:purchase_orders_details,invoice',
            'motor_id' => 'required|exists:master_motors,id',
            'spare_part_id' => 'required|exists:master_spare_parts,id',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $maxOrder = PurchaseOrdersDetails::max('order') ?? 0;
        $newOrder = $maxOrder + 1;

        // Calculate total_harga
        $total_harga = $request->jumlah * $request->harga;

        PurchaseOrdersDetails::create([
            'po_id' => $request->po_id,
            'invoice' => $request->invoice,
            'motor_id' => $request->motor_id,
            'spare_part_id' => $request->spare_part_id,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'total_harga' => $total_harga,
            'order' => $newOrder
        ]);

        return redirect()->route('purchase_orders_details.index')
            ->with('success', 'Purchase Order Detail created successfully.');
    }

    public function show($id)
    {
        $purchaseOrderDetail = PurchaseOrdersDetails::with(['purchaseOrder', 'motor', 'sparePart'])->findOrFail($id);

        return view('layouts.purchase_orders_details.show', compact('purchaseOrderDetail'));
    }

    public function destroy($id)
    {
        $purchaseOrderDetail = PurchaseOrdersDetails::findOrFail($id);
        $deletedOrder = $purchaseOrderDetail->order;

        $purchaseOrderDetail->delete();

        PurchaseOrdersDetails::where('order', '>', $deletedOrder)->decrement('order');

        return redirect()->route('purchase_orders_details.index')->with('message', 'Purchase Order Detail deleted successfully.');
    }
}
