<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\MotorUnit;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseOrdersDetails;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::query();

        if ($request->has('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', $search)
                    ->orWhereHas('motor', function ($q) use ($search) {
                        $q->where('nama_motor', 'like', $search);
                    })
                    ->orWhereHas('sparePart', function ($q) use ($search) {
                        $q->where('nama_spare_part', 'like', $search);
                    });
            });
        }

        if ($request->has('type') && in_array($request->type, ['in', 'out'])) {
            $query->where('type', $request->type);
        }

        $motors = $query->whereNotNull('motor_id')
            ->with('motor')
            ->orderBy('created_at', 'desc')
            ->get();

        $spareParts = Stock::query()
            ->whereNotNull('spare_part_id')
            ->with('sparePart')
            ->orderBy('created_at', 'desc')
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

    public function editPricing($id)
    {
        $stock = Stock::findOrFail($id);
        return view('layouts.stock.edit-pricing', compact('stock'));
    }

    public function updateDetails(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        DB::beginTransaction();
        try {
            $stock->update([
                'harga_jual' => round($request->harga_jual, 2),
                'diskon_persen' => round($request->diskon_persen, 2),
                'diskon_nilai' => round($request->diskon_nilai, 2),
                'harga_jual_diskon' => round($request->harga_jual_diskon, 2),
            ]);

            // Tambahkan unit baru jika nomor rangka dan mesin diisi
            if ($request->filled('nomor_rangka') && $request->filled('nomor_mesin')) {
                $stock->motorUnits()->create([
                    'nomor_rangka' => $request->nomor_rangka,
                    'nomor_mesin' => $request->nomor_mesin,
                ]);
                $stock->increment('unit_count');
            }

            DB::commit();
            return redirect()->route('stock.index')->with('message', 'Stock details updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    public function showMotorUnits($stockId)
    {
        $stock = Stock::with('motorUnits', 'motor')->findOrFail($stockId);
        return view('layouts.stock.motor-units', compact('stock'));
    }

    public function addMotorUnit(Request $request, $stockId)
    {
        $stock = Stock::findOrFail($stockId);
        
        $request->validate([
            'nomor_rangka' => 'required|unique:motor_units',
            'nomor_mesin' => 'required|unique:motor_units',
        ]);
    
        DB::beginTransaction();
        try {
            $motorUnit = new MotorUnit([
                'nomor_rangka' => $request->nomor_rangka,
                'nomor_mesin' => $request->nomor_mesin,
                'status' => MotorUnit::STATUS_AVAILABLE,
            ]);
    
            $stock->motorUnits()->save($motorUnit);
            $stock->increment('unit_count');
            
            DB::commit();
            return redirect()->back()->with('success', 'Unit motor berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan unit motor: ' . $e->getMessage());
        }
    }

    public function updateMotorUnitStatus(Request $request, $motorUnitId)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [MotorUnit::STATUS_AVAILABLE, MotorUnit::STATUS_SOLD]),
        ]);

        $motorUnit = MotorUnit::findOrFail($motorUnitId);
        $oldStatus = $motorUnit->status;
        $motorUnit->update(['status' => $request->status]);

        // Update stock count if necessary
        if ($oldStatus !== $request->status) {
            if ($request->status === MotorUnit::STATUS_SOLD) {
                $motorUnit->stock->decrement('jumlah');
            } else {
                $motorUnit->stock->increment('jumlah');
            }
        }

        return redirect()->back()->with('success', 'Status unit motor berhasil diperbarui');
    }

    public function deleteMotorUnit($motorUnitId)
    {
        DB::beginTransaction();
        try {
            $motorUnit = MotorUnit::findOrFail($motorUnitId);
            $stock = $motorUnit->stock;

            $motorUnit->delete();
            $stock->decrement('unit_count');

            DB::commit();
            return redirect()->back()->with('success', 'Unit motor berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus unit motor: ' . $e->getMessage());
        }
    }

    public function searchMotorUnit(Request $request)
    {
        $query = $request->get('query');
        $motorUnits = MotorUnit::where('nomor_rangka', 'like', "%$query%")
            ->orWhere('nomor_mesin', 'like', "%$query%")
            ->get();

        return view('stock.search-results', compact('motorUnits'));
    }

    public function addToStock($invoice)
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::where('invoice', $invoice)->firstOrFail();
            $purchaseOrderDetails = $purchaseOrder->purchaseOrderDetails;

            foreach ($purchaseOrderDetails as $detail) {
                $lastOrder = Stock::max('order') ?? 0;
                $newOrder = $lastOrder + 1;

                $stock = new Stock();
                $stock->purchase_order_detail_id = $detail->id;
                $stock->motor_id = $detail->motor_id;
                $stock->spare_part_id = $detail->spare_part_id;
                $stock->warna_id = $detail->warna_id;
                $stock->jumlah = $detail->jumlah;
                $stock->harga_beli = $detail->harga;
                $stock->type = 'in';
                $stock->order = $newOrder;

                // Nomor rangka dan nomor mesin akan diisi nanti
                $stock->nomor_rangka = null;
                $stock->nomor_mesin = null;

                // Harga jual dan diskon akan diisi nanti
                $stock->harga_jual = null;
                $stock->harga_jual_diskon = null;
                $stock->diskon_persen = null;
                $stock->diskon_nilai = null;

                $stock->save();

                Log::info("Added {$stock->jumlah} of " . ($stock->motor_id ? "motor ID {$stock->motor_id}" : "spare part ID {$stock->spare_part_id}") . " to stock with type: {$stock->type}, order: {$stock->order}");
            }

            DB::commit();
            Log::info("Successfully added stock for invoice: {$invoice}");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error adding stock for invoice {$invoice}: " . $e->getMessage());
            throw $e;
        }
    }

    public function removeFromStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'harga_jual_diskon' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $stock = Stock::findOrFail($request->product_id);

            if ($stock->jumlah < $request->quantity) {
                throw new \Exception("Insufficient stock for product ID: {$request->product_id}");
            }

            $lastOrder = Stock::max('order') ?? 0;
            $newOrder = $lastOrder + 1;

            $newStock = new Stock();
            $newStock->fill($stock->toArray());
            $newStock->jumlah = $request->quantity;
            $newStock->type = 'out';
            $newStock->order = $newOrder;
            $newStock->harga_jual = $request->harga_jual;
            $newStock->harga_jual_diskon = $request->harga_jual_diskon;

            // Hitung diskon
            if ($request->harga_jual > $request->harga_jual_diskon) {
                $newStock->diskon_nilai = $request->harga_jual - $request->harga_jual_diskon;
                $newStock->diskon_persen = ($newStock->diskon_nilai / $request->harga_jual) * 100;
            } else {
                $newStock->diskon_nilai = 0;
                $newStock->diskon_persen = 0;
            }

            $newStock->nomor_rangka = null;
            $newStock->nomor_mesin = null;

            $newStock->save();

            $stock->jumlah -= $request->quantity;
            $stock->save();

            DB::commit();
            Log::info("Removed {$request->quantity} of " . ($stock->motor_id ? "motor ID {$stock->motor_id}" : "spare part ID {$stock->spare_part_id}") . " from stock with type: out, order: {$newStock->order}, harga jual: {$request->harga_jual}, harga jual diskon: {$request->harga_jual_diskon}");

            return redirect()->back()->with('success', 'Stock removed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error removing from stock: " . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function applyDiscount(Request $request, $stockId)
    {
        $request->validate([
            'diskon_tipe' => 'required|in:persen,nilai',
            'diskon_value' => 'required|numeric|min:0',
        ]);

        $stock = Stock::findOrFail($stockId);

        if ($request->diskon_tipe === 'persen') {
            $stock->diskon_persen = $request->diskon_value;
            $stock->diskon_nilai = $stock->harga_jual * ($request->diskon_value / 100);
        } else {
            $stock->diskon_nilai = $request->diskon_value;
            $stock->diskon_persen = ($request->diskon_value / $stock->harga_jual) * 100;
        }

        $stock->harga_jual_diskon = $stock->harga_jual - $stock->diskon_nilai;
        $stock->save();

        return response()->json([
            'message' => 'Diskon berhasil diterapkan',
            'stock' => $stock
        ]);
    }

    public function updateStockFromCompletedOrders()
    {
        Log::info("Updating stock from completed orders");

        try {
            DB::beginTransaction();

            $completedOrders = PurchaseOrdersDetails::where('status', 'completed')
                ->whereDoesntHave('stock')
                ->get();

            Log::info("Found " . $completedOrders->count() . " completed orders to process");

            foreach ($completedOrders as $detail) {
                if ($detail->motor_id) {
                    $stock = Stock::create([
                        'motor_id' => $detail->motor_id,
                        'jumlah' => $detail->jumlah,
                        'type' => 'in',
                        'purchase_order_detail_id' => $detail->id,
                        'order' => $this->generateOrderNumber(),
                        'harga_beli' => $detail->harga,
                        'harga_jual' => $detail->harga * 1.2, // Asumsi markup 20%
                        'warna_id' => $detail->warna_id,
                    ]);
                    Log::info("Added motor to stock: " . json_encode($stock));
                } elseif ($detail->spare_part_id) {
                    $stock = Stock::create([
                        'spare_part_id' => $detail->spare_part_id,
                        'jumlah' => $detail->jumlah,
                        'type' => 'in',
                        'purchase_order_detail_id' => $detail->id,
                        'order' => $this->generateOrderNumber(),
                        'harga_beli' => $detail->harga,
                        'harga_jual' => $detail->harga * 1.2, // Asumsi markup 20%
                        'warna_id' => $detail->warna_id,
                    ]);
                    Log::info("Added spare part to stock: " . json_encode($stock));
                }
            }

            DB::commit();
            Log::info("Stock update completed successfully");

            return redirect()->back()->with('message', 'Stock updated successfully from completed orders.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating stock: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }
}
