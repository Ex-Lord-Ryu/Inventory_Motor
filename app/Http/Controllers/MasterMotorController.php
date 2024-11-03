<?php

namespace App\Http\Controllers;

use App\Models\MasterMotor;
use Illuminate\Http\Request;

class MasterMotorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sortBy', 'order'); // Default sort by order
        $order = $request->get('order', 'asc'); // Default order is ascending
    
        $motor = MasterMotor::when($search, function ($query) use ($search) {
            return $query->where('order', 'like', '%' . $search . '%')
                         ->orWhere('nama_motor', 'like', '%' . $search . '%');
        })->orderBy($sortBy, $order)->paginate(10);
    
        return view('layouts.master_motor.index', compact('motor', 'sortBy', 'order'));
    }     

    public function create()
    {
        return view('layouts.master_motor.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_motor' => 'required|string|max:255',
        ]);
    
        $highestOrder = MasterMotor::max('order');
        $validatedData['order'] = $highestOrder ? $highestOrder + 1 : 1;
    
        MasterMotor::create($validatedData);
    
        return redirect()->route('master_motor.index')->with('message', 'Motor created successfully.');
    }
    

    public function edit($id)
    {
        $motor = MasterMotor::findOrFail($id);
        return view('layouts.master_motor.edit', compact('motor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_motor' => 'required|string|max:255',
        ]);

        $motor = MasterMotor::findOrFail($id);
        $motor->update($request->all());

        return redirect()->route('master_motor.index')->with('message', 'Motor updated successfully.');
    }

    public function destroy($id)
    {
        $motor = MasterMotor::findOrFail($id);
        $orderToDelete = $motor->order; 

        $motor->delete();


        MasterMotor::where('order', '>', $orderToDelete)
            ->decrement('order'); 

        return redirect()->route('master_motor.index')->with('message', 'Motor deleted successfully.');
    }
}
