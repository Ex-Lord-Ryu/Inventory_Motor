<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->input('sortBy', 'id');
        $order = $request->input('order', 'asc');
        $search = $request->input('search');

        $distributor = Distributor::query()
            ->when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                    ->orWhere('name_Vendor', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $order)
            ->paginate(10);

        return view('layouts.distributor.index', compact('distributor', 'sortBy', 'order'));
    }

    public function create()
    {
        return view('layouts.distributor.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_Vendor' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $highestOrder = Distributor::max('order');
        
        $validatedData['order'] = $highestOrder ? $highestOrder + 1 : 1;

        Distributor::create($validatedData);

        return redirect()->route('distributor.index')->with('success', 'Data vendor berhasil disimpan!');
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        return view('layouts.distributor.edit', compact('distributor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_Vendor' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
        ]);

        $distributor = Distributor::findOrFail($id);
        $distributor->update([
            'name_Vendor' => $request->name_Vendor,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('distributor.index')->with('message', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);

        $deletedOrder = $distributor->order;

        $distributor->delete();

        Distributor::where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()->route('distributor.index')->with('message', 'Vendor deleted successfully.');
    }
}
