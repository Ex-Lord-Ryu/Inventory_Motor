<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\user_management;
use Illuminate\Http\Request;

class user_managementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->input('sortBy', 'id'); // default sort by id
        $order = $request->input('order', 'asc');  // default order ascending
        $search = $request->input('search');
    
        $query = User::query();
    
        // Add search functionality
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
        }
    
        // Add sorting
        $query->orderBy($sortBy, $order);
    
        $user_management = $query->paginate(5)->appends($request->query());
    
        return view('layouts.user_management.index', compact('user_management', 'sortBy', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('layouts.user_management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:superadmin,admin,operasional,finance,sales',
        ]);
    
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'email_verified_at' => Carbon::now(),
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('User creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
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
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('User deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}
