<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrdersDetails; // Update the model import
use Illuminate\Http\Request;

class PurchaseOrdersDetailsController extends Controller
{
    public function index(Request $request)
    {
        $purchaseOrdersDetails = PurchaseOrdersDetails::with(['purchaseOrder', 'motor', 'sparePart'])
            ->orderBy($request->get('sortBy', 'id'), $request->get('order', 'asc'))
            ->paginate(10);

        return view('layouts.purchase_orders_details.index', compact('purchaseOrdersDetails'));
    }

    public function create()
    {
        // Assuming you have a way to get motor and spare part data
        return view('layouts.purchase_orders_details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'invoice' => 'required|string|unique:purchase_orders_details',
            'motor_id' => 'required|exists:master_motors,id',
            'spare_part_id' => 'required|exists:master_spare_parts,id',
        ]);

        PurchaseOrdersDetails::create($request->all());

        return redirect()->route('purchase_orders_details.index')->with('message', 'Purchase Order Detail created successfully.');
    }

    public function edit($id)
    {
        $purchaseOrderDetail = PurchaseOrdersDetails::findOrFail($id);
        return view('layouts.purchase_orders_details.edit', compact('purchaseOrderDetail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'invoice' => 'required|string|unique:purchase_orders_details,invoice,' . $id,
            'motor_id' => 'required|exists:master_motors,id',
            'spare_part_id' => 'required|exists:master_spare_parts,id',
        ]);

        $purchaseOrderDetail = PurchaseOrdersDetails::findOrFail($id);
        $purchaseOrderDetail->update($request->all());

        return redirect()->route('purchase_orders_details.index')->with('message', 'Purchase Order Detail updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrderDetail = PurchaseOrdersDetails::findOrFail($id);
        $purchaseOrderDetail->delete();

        return redirect()->route('purchase_orders_details.index')->with('message', 'Purchase Order Detail deleted successfully.');
    }
}
