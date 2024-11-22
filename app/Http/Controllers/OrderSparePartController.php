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
    public function index()
    {
        Log::info('Accessing OrderSparePartController@index');
        try {
            Log::info('Fetching available spare parts');
            $availableSpareParts = StockSparePart::where('type', 'in')
                ->where('jumlah', '>', 0)
                ->with('sparePart')
                ->get();
            Log::info('Available spare parts fetched: ' . $availableSpareParts->count());

            $groupedSpareParts = $availableSpareParts->groupBy('spare_part_id');
            Log::info('Spare parts grouped: ' . $groupedSpareParts->count());

            $availableSpareParts = $groupedSpareParts->map(function ($group) {
                return [
                    'spare_part' => $group->first()->sparePart,
                    'stock' => $group->sum('jumlah'),
                ];
            })->values()->toArray();
            Log::info('Available spare parts processed');

            Log::info('Fetching all spare parts');
            $allSpareParts = StockSparePart::where('type', 'in')
                ->where('jumlah', '>', 0)
                ->with('sparePart')
                ->get()
                ->pluck('sparePart')
                ->unique('id')
                ->values();
            Log::info('All spare parts fetched: ' . $allSpareParts->count());

            Log::info('Fetching recent orders');
            $recentOrders = OrderSparePart::with(['sparePart', 'user'])
                ->whereHas('sparePart')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            Log::info('Recent orders fetched: ' . $recentOrders->count());

            $currentUser = Auth::user();
            Log::info('Current user fetched: ' . $currentUser->id);

            Log::info('Rendering view');
            return view('layouts.order_spare_parts.index', compact('availableSpareParts', 'allSpareParts', 'recentOrders', 'currentUser'));
        } catch (\Exception $e) {
            Log::error('Error in OrderSparePartController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while loading the page. Please check the logs for more information.');
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
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            $stockSparePart = StockSparePart::where('spare_part_id', $request->spare_part_id)
                ->where('type', 'in')
                ->first();

            if (!$stockSparePart || $stockSparePart->jumlah < $request->jumlah) {
                throw new \Exception('Not enough stock available.');
            }

            $orderData = [
                'user_id' => Auth::id(),
                'spare_part_id' => $request->spare_part_id,
                'jumlah' => $request->jumlah,
                'harga_jual' => $stockSparePart->harga_jual,
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
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
