<?php

namespace App\Http\Controllers;

use App\Models\StockSparePart;
use App\Models\SoldSparePart;
use App\Models\OrderSparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderSparePartController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Accessing OrderSparePartController@index');
        try {
            Log::info('Fetching available spare parts');
            $availableSpareParts = StockSparePart::where('type', 'in')
                ->where('jumlah', '>', 0)
                ->with('sparePart')
                ->get();

            $groupedSpareParts = $availableSpareParts->groupBy('spare_part_id');
            
            $availableSpareParts = $groupedSpareParts->map(function ($group) {
                return [
                    'spare_part' => $group->first()->sparePart,
                    'stock' => $group->sum('jumlah'),
                ];
            })->values()->toArray();

            $query = OrderSparePart::with(['sparePart', 'user'])
                ->whereHas('sparePart')
                ->orderBy('tanggal_terjual', 'desc');

            // Hanya terapkan filter jika tombol filter ditekan
            if ($request->has('filter')) {
                if ($request->filled('month') && $request->month !== '') {
                    $query->whereMonth('tanggal_terjual', $request->month);
                }
                if ($request->filled('year') && $request->year !== '') {
                    $query->whereYear('tanggal_terjual', $request->year);
                }
            } else {
                // Default ke bulan dan tahun saat ini jika tidak ada filter
                $query->whereMonth('tanggal_terjual', date('n'))
                      ->whereYear('tanggal_terjual', date('Y'));
            }

            $recentOrders = $query->get();
            $currentUser = Auth::user();

            return view('layouts.order_spare_parts.index', compact('availableSpareParts', 'recentOrders', 'currentUser'));
        } catch (\Exception $e) {
            Log::error('Error in OrderSparePartController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the page.');
        }
    }

    public function store(Request $request)
    {
        Log::info('Accessing OrderSparePartController@store');
        Log::info('Received request data:', $request->all());

        try {
            $validated = $request->validate([
                'spare_part_id' => 'required|exists:master_spare_parts,id',
                'jumlah' => 'required|integer|min:1',
            ]);
            Log::info('Request data validated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return response()->json(['message' => $e->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $stockSparePart = StockSparePart::where('spare_part_id', $request->spare_part_id)
                ->where('type', 'in')
                ->where('jumlah', '>', 0)
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$stockSparePart || $stockSparePart->jumlah < $request->jumlah) {
                throw new \Exception('Not enough stock available.');
            }
            
            if ($stockSparePart->harga_jual === null) {
                throw new \Exception('Selling price is not set for this spare part.');
            }

            $orderData = [
                'user_id' => Auth::id(),
                'spare_part_id' => $request->spare_part_id,
                'jumlah' => $request->jumlah,
                'harga_jual' => $stockSparePart->harga_jual,
                'tanggal_terjual' => now(),
            ];

            Log::info('Attempting to create order with data:', $orderData);

            $order = OrderSparePart::create($orderData);

            Log::info('Order created: ' . $order->id);

            SoldSparePart::create([
                'spare_part_id' => $stockSparePart->spare_part_id,
                'jumlah' => $request->jumlah,
                'harga_jual' => $stockSparePart->harga_jual,
                'tanggal_terjual' => now(),
            ]);

            $stockSparePart->jumlah -= $request->jumlah;
            if ($stockSparePart->jumlah == 0) {
                $stockSparePart->delete();
            } else {
                $stockSparePart->save();
            }
            Log::info('Stock spare part updated: ' . $stockSparePart->id);

            DB::commit();
            Log::info('Transaction committed successfully');
            return redirect()->route('order_spare_parts.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in OrderSparePartController@store: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
