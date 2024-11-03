<?php

namespace App\Http\Controllers;

use App\Models\MasterSparePart;
use Illuminate\Http\Request;

class MasterSparePartController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sortBy', 'order');
        $order = $request->input('order', 'asc');
    
        $master_spare_parts = MasterSparePart::when($search, function ($query, $search) {
                return $query->where('nama_spare_part', 'like', '%' . $search . '%')
                             ->orWhere('order', 'like', '%' . $search . '%');
            })
            ->orderBy($sortBy, $order)
            ->paginate(10)
            ->appends(['sortBy' => $sortBy, 'order' => $order, 'search' => $search]);
    
        return view('layouts.master_spare_parts.index', compact('master_spare_parts', 'sortBy', 'order'));
    }  
    
    public function create()
    {
        return view('layouts.master_spare_parts.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nama_spare_part' => 'required|string|max:255',
        ]);
    
        $highestOrder = MasterSparePart::max('order');
    
        $validatedData['order'] = $highestOrder ? $highestOrder + 1 : 1;
    
        MasterSparePart::create($validatedData);
    
        return redirect()->route('master_spare_parts.index')->with('message', 'Spare part created successfully.');
    }

    public function edit($id)
    {
        $master_spare_part = MasterSparePart::findOrFail($id);
        return view('layouts.master_spare_parts.edit', compact('master_spare_part'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_spare_part' => 'required|string|max:255',
        ]);

        $master_spare_part = MasterSparePart::findOrFail($id);
        $master_spare_part->update($request->all());

        return redirect()->route('master_spare_parts.index')->with('message', 'Spare part updated successfully.');
    }

    public function destroy($id)
    {
        $master_spare_part = MasterSparePart::findOrFail($id);
        $orderToDelete = $master_spare_part->order;
    
        $master_spare_part->delete();
    
        MasterSparePart::where('order', '>', $orderToDelete)->decrement('order');
    
        return redirect()->route('master_spare_parts.index')->with('message', 'Spare part deleted successfully.');
    }
    
}
