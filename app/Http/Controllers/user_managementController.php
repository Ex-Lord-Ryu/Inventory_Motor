<?php

namespace App\Http\Controllers;

use App\Models\user_management;
use Illuminate\Http\Request;

class user_managementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        if ($search) {
            $data['user_management'] = user_management::where('id', 'like', "%{$search}%")->get();
        } else {
            $data['user_management'] = user_management::all();
        }
        return view('layouts.user_management.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(user_management $user_management)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $user_management = user_management::find($id);
        return view('layouts.user_management.edit', compact('user_management'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user_management $user_management, $id)
    {
        //
        $user_management = user_management::find($id);
        $user_management->role = $request->role;
        $user_management->save();
        return redirect()->route('user_management.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_management $user_management)
    {
        //
        $user_management->delete();
    }
}
