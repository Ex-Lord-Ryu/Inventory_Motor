<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::query();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('type') && in_array($request->type, ['in', 'out'])) {
            $query->where('type', $request->type);
        }

        $motors = $query->whereNotNull('motor_id')
                        ->orderBy('order', 'asc')
                        ->get();

        $spareParts = $query->whereNotNull('spare_part_id')
                            ->orderBy('order', 'asc')
                            ->get();

        return view('layouts.stock.index', compact('motors', 'spareParts'));
    }

    public function updateType(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
        ]);

        Stock::query()->update(['type' => $request->type]);

        return redirect()->route('stock.index')->with('message', 'Stock types updated successfully');
    }
}