<?php

namespace App\Http\Controllers;

use App\Models\StockMotor;
use App\Models\SoldMotor;
use App\Models\OrderMotor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderMotorController extends Controller
{
    public function index()
    {
        Log::info('Accessing OrderMotorController@index');
        try {
            $availableMotors = StockMotor::where('type', 'in')
                ->whereNotNull('nomor_rangka')
                ->whereNotNull('nomor_mesin')
                ->with(['motor', 'warna'])
                ->get()
                ->groupBy('motor_id')
                ->map(function ($group) {
                    return [
                        'motor' => $group->first()->motor,
                        'warnas' => $group->groupBy('warna_id')->map(function ($warnaGroup) {
                            return [
                                'id' => $warnaGroup->first()->warna->id,
                                'nama' => $warnaGroup->first()->warna->nama_warna,
                                'stock' => $warnaGroup->count(),
                            ];
                        })->values()->toArray(),
                    ];
                })->values()->toArray();

            // Ambil semua warna yang unik
            $allWarnas = StockMotor::where('type', 'in')
                ->whereNotNull('nomor_rangka')
                ->whereNotNull('nomor_mesin')
                ->with('warna')
                ->get()
                ->pluck('warna')
                ->unique('id')
                ->values();

            $recentOrders = OrderMotor::with(['motor', 'warna', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $currentUser = Auth::user();

            Log::info('Index data retrieved successfully');
            return view('layouts.order_motor.index', compact('availableMotors', 'allWarnas', 'recentOrders', 'currentUser'));
        } catch (\Exception $e) {
            Log::error('Error in OrderMotorController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the page.');
        }
    }

    public function store(Request $request)
    {
        Log::info('Accessing OrderMotorController@store');
        Log::info('Received request data:', $request->all());

        try {
            $validated = $request->validate([
                'motor_id' => 'required|exists:master_motors,id',
                'warna_id' => 'required|exists:master_warnas,id',
                'jumlah' => 'required|integer|min:1',
            ]);
            Log::info('Request data validated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            $stockMotors = StockMotor::where('motor_id', $request->motor_id)
                ->where('warna_id', $request->warna_id)
                ->where('type', 'in')
                ->whereNotNull('nomor_rangka')
                ->whereNotNull('nomor_mesin')
                ->take($request->jumlah)
                ->get();

            Log::info('Retrieved stock motors: ' . $stockMotors->count());

            if ($stockMotors->count() < $request->jumlah) {
                throw new \Exception('Not enough stock available.');
            }

            foreach ($stockMotors as $stockMotor) {
                $orderData = [
                    'user_id' => Auth::id(),
                    'motor_id' => $request->motor_id,
                    'warna_id' => $request->warna_id,
                    'jumlah' => 1,
                    'nomor_rangka' => $stockMotor->nomor_rangka,
                    'nomor_mesin' => $stockMotor->nomor_mesin,
                    'harga_jual' => $stockMotor->harga_jual, // Tambahkan ini
                ];

                Log::info('Attempting to create order with data:', $orderData);

                $order = OrderMotor::create($orderData);

                Log::info('Order created: ' . $order->id);

                SoldMotor::create([
                    'order_id' => $order->id,
                    'motor_id' => $stockMotor->motor_id,
                    'warna_id' => $stockMotor->warna_id,
                    'nomor_rangka' => $stockMotor->nomor_rangka,
                    'nomor_mesin' => $stockMotor->nomor_mesin,
                    'harga_jual' => $stockMotor->harga_jual,
                    'tanggal_terjual' => now(),
                ]);

                $stockMotor->delete();
                Log::info('Stock motor sold and deleted: ' . $stockMotor->id);
            }

            DB::commit();
            Log::info('Transaction committed successfully');
            return redirect()->route('order_motor.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in OrderMotorController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
