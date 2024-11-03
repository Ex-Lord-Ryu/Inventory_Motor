<?php

namespace App\Http\Controllers;

use App\Models\MasterWarna;
use Illuminate\Http\Request;

class MasterWarnaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sortBy', 'id');
        $order = $request->input('order', 'asc');
    
        $warna = MasterWarna::when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                    ->orWhere('nama_warna', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $order)
            ->paginate(10);
    
        return view('layouts.master_warna.index', compact('warna', 'search', 'sortBy', 'order'));
    }    

    public function create()
    {
        return view('layouts.master_warna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:255',
            'id' => 'unique:master_warna,id' 
        ]);


        $nama_warna = $request->input('nama_warna');

        $id = 'clr_' . str_replace(' ', '_', strtolower($nama_warna));

        $warna = new MasterWarna();
        $warna->id = $id;
        $warna->nama_warna = $nama_warna;
        $warna->save();

        return redirect()->route('master_warna.index')->with('message', 'Warna berhasil ditambahkan');
    }


    public function edit($id)
    {
        $warna = MasterWarna::findOrFail($id);
        return view('layouts.master_warna.edit', compact('warna'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:255',
            'id' => 'unique:master_warna,id' 
        ]);

        $warna = MasterWarna::findOrFail($id);

        $old_nama_warna = $warna->nama_warna;
        $new_nama_warna = $request->input('nama_warna');

        $warna->nama_warna = $new_nama_warna;

        if ($old_nama_warna !== $new_nama_warna) {
            $warna->id = 'clr_' . str_replace(' ', '_', strtolower($new_nama_warna));
        }

        // Save the changes
        $warna->save();

        return redirect()->route('master_warna.index')->with('message', 'Warna berhasil diupdate');
    }

    public function destroy($id)
    {
        $warna = MasterWarna::findOrFail($id);
        $warna->delete();

        return redirect()->route('master_warna.index')->with('message', 'Color deleted successfully.');
    }
}
