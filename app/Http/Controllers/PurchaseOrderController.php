<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

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

        $purchaseOrder->update([
            'vendor_id' => $request->vendor_id,
            'status' => $request->status,
        ]);

        return redirect()->route('purchase_orders.index')->with('message', 'Purchase Order updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $deletedOrder = $purchaseOrder->order;

        $purchaseOrder->delete();

        PurchaseOrder::where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()->route('purchase_orders.index')->with('message', 'Purchase Order berhasil dihapus.');
    }
}
