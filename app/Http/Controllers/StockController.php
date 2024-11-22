<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\MotorUnit;
use App\Models\SoldMotor;
use App\Models\StockMotor;
use App\Models\MasterMotor;
use App\Models\MasterWarna;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\SoldSparePart;
use App\Models\StockSparePart;
use App\Models\MasterSparePart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseOrdersDetails;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMotor::query();
        $sparePartQuery = StockSparePart::query();

        if ($request->has('search')) {
            $search = '%' . $request->search . '%';
            $motorSearchCondition = function ($q) use ($search) {
                $q->where('type', 'like', $search)
                    ->orWhereHas('motor', function ($q) use ($search) {
                        $q->where('nama_motor', 'like', $search);
                    });
            };
            $sparePartSearchCondition = function ($q) use ($search) {
                $q->where('type', 'like', $search)
                    ->orWhereHas('sparePart', function ($q) use ($search) {
                        $q->where('nama_spare_part', 'like', $search);
                    });
            };

            $query->where($motorSearchCondition);
            $sparePartQuery->where($sparePartSearchCondition);
        }

        if ($request->has('type') && in_array($request->type, ['in', 'out'])) {
            $query->where('type', $request->type);
            $sparePartQuery->where('type', $request->type);
        }

        $motorStocks = $query->with(['motor', 'warna'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Menggabungkan stok motor dengan nama dan warna yang sama, hanya untuk tipe 'in'
        $motors = $motorStocks->groupBy(function ($item) {
            return $item->motor->nama_motor . '-' . ($item->warna->nama_warna ?? 'N/A');
        })->map(function ($group) {
            $first = $group->first();
            $first->jumlah = $group->where('type', 'in')->count(); // Hitung jumlah hanya untuk tipe 'in'
            return $first;
        })->values();

        $spareParts = $sparePartQuery
            ->with('sparePart')
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedMotors = $motors->groupBy('motor.nama_motor');

        return view('layouts.stock.index', compact('motors', 'spareParts', 'groupedMotors'));
    }

    public function updateType(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
        ]);

        Stock::query()->update(['type' => $request->type]);

        return redirect()->route('stock.index')->with('message', 'Stock types updated successfully');
    }

    public function editPricing($id, $type)
    {
        if ($type === 'motor') {
            $stock = StockMotor::findOrFail($id);
        } elseif ($type === 'spare_part') {
            $stock = StockSparePart::findOrFail($id);
        } else {
            abort(404);
        }
        return view('layouts.stock.edit-pricing', compact('stock', 'type'));
    }

    public function updateDetails(Request $request, $id, $type)
    {
        $rules = [
            'harga_jual' => 'required|numeric|min:0',
        ];

        if ($type === 'spare_part') {
            $rules['jumlah'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        if ($type === 'motor') {
            $stock = StockMotor::findOrFail($id);
        } elseif ($type === 'spare_part') {
            $stock = StockSparePart::findOrFail($id);
        } else {
            abort(404);
        }

        DB::beginTransaction();
        try {
            if ($type === 'spare_part') {
                $masterSparePart = $stock->sparePart;
                $oldJumlah = $stock->jumlah;
                $newJumlah = $request->jumlah * $masterSparePart->unit_satuan;

                // Adjust harga_beli based on the new quantity
                $totalNilai = $stock->harga_beli * $oldJumlah;
                $newHargaBeli = $totalNilai / $newJumlah;

                $stock->update([
                    'jumlah' => $newJumlah,
                    'harga_beli' => round($newHargaBeli, 2),
                    'harga_jual' => round($request->harga_jual, 2),
                ]);
            } else {
                try {
                    // For motors, update all stocks with the same motor name
                    $motorName = $stock->motor->nama_motor;
                    $affected = StockMotor::whereHas('motor', function ($query) use ($motorName) {
                        $query->where('nama_motor', $motorName);
                    })->update([
                        'harga_jual' => round($request->harga_jual, 2),
                    ]);

                    Log::info("Updated {$affected} motor stocks for {$motorName} with new price: {$request->harga_jual}");
                } catch (\Exception $e) {
                    Log::error("Failed to update motor stocks: " . $e->getMessage());
                    throw $e;
                }
            }

            DB::commit();
            return redirect()->route('stock.index')->with('message', 'Stock details updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    public function allStock()
    {
        $motorStocks = StockMotor::with(['motor', 'warna'])
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedMotors = $motorStocks->groupBy('motor.nama_motor');

        $spareParts = StockSparePart::with('sparePart')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.stock.all_stock', compact('groupedMotors', 'spareParts'));
    }

    public function addToStock($invoice)
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::where('invoice', $invoice)->firstOrFail();
            $purchaseOrderDetails = $purchaseOrder->purchaseOrderDetails;

            foreach ($purchaseOrderDetails as $detail) {
                if ($detail->motor_id) {
                    // Untuk setiap unit motor, buat satu entri
                    for ($i = 0; $i < $detail->jumlah; $i++) {
                        $stock = new StockMotor();
                        $stock->motor_id = $detail->motor_id;
                        $stock->warna_id = $detail->warna_id;
                        $stock->purchase_order_detail_id = $detail->id;
                        $stock->harga_beli = $detail->harga;
                        $stock->type = 'in';
                        $stock->harga_jual = null;  // Anda mungkin ingin mengatur ini
                        $stock->nomor_rangka = null;  // Anda perlu mengatur ini secara unik
                        $stock->nomor_mesin = null;   // Anda perlu mengatur ini secara unik
                        $stock->save();

                        Log::info("Added 1 motor (ID: {$stock->motor_id}) to stock with type: {$stock->type}");
                    }
                } elseif ($detail->spare_part_id) {
                    // Untuk spare part, gunakan unit_satuan
                    $sparePart = MasterSparePart::findOrFail($detail->spare_part_id);
                    $unitSatuan = $sparePart->unit_satuan;

                    $stock = new StockSparePart();
                    $stock->spare_part_id = $detail->spare_part_id;
                    $stock->purchase_order_detail_id = $detail->id;
                    $stock->jumlah = $detail->jumlah * $unitSatuan;
                    $stock->harga_beli = $detail->harga / $unitSatuan;
                    $stock->type = 'in';
                    $stock->harga_jual = null;  // Anda mungkin ingin mengatur ini
                    $stock->save();

                    Log::info("Added {$stock->jumlah} units of spare part (ID: {$stock->spare_part_id}) to stock with type: {$stock->type}");
                }
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

    public function inputMotorData()
    {
        $stockMotors = StockMotor::where('type', 'in')
            ->whereNull('nomor_rangka')
            ->whereNull('nomor_mesin')
            ->with(['motor', 'warna'])
            ->select('id', 'motor_id', 'warna_id')
            ->get();

        $motors = $stockMotors->pluck('motor')->unique('id');
        $warnas = $stockMotors->pluck('warna')->unique('id');

        return view('layouts.stock.motor-input', compact('stockMotors', 'motors', 'warnas'));
    }

    public function storeMotorData(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stock_motors,id',
            'nomor_rangka' => 'required|unique:stock_motors',
            'nomor_mesin' => 'required|unique:stock_motors',
        ]);

        $stockMotor = StockMotor::findOrFail($request->stock_id);
        $stockMotor->update([
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin' => $request->nomor_mesin,
        ]);

        return redirect()->route('stock.index')->with('message', 'Data motor berhasil diupdate');
    }

    public function sellMotor($id)
    {
        DB::beginTransaction();
        try {
            $stockMotor = StockMotor::findOrFail($id);

            SoldMotor::create([
                'motor_id' => $stockMotor->motor_id,
                'warna_id' => $stockMotor->warna_id,
                'nomor_rangka' => $stockMotor->nomor_rangka,
                'nomor_mesin' => $stockMotor->nomor_mesin,
                'harga_jual' => $stockMotor->harga_jual,
                'tanggal_terjual' => now(),
            ]);

            $stockMotor->delete();

            DB::commit();
            return redirect()->route('stock.index')->with('message', 'Motor berhasil dijual');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menjual motor: ' . $e->getMessage());
        }
    }

    public function sellSparePart($id)
    {
        DB::beginTransaction();
        try {
            $stockSparePart = StockSparePart::findOrFail($id);

            SoldSparePart::create([
                'spare_part_id' => $stockSparePart->spare_part_id,
                'jumlah' => $stockSparePart->jumlah,
                'harga_jual' => $stockSparePart->harga_jual,
                'tanggal_terjual' => now(),
            ]);

            $stockSparePart->delete();

            DB::commit();
            return redirect()->route('stock.index')->with('message', 'Spare part berhasil dijual');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menjual spare part: ' . $e->getMessage());
        }
    }

    public function soldItems()
    {
        $soldMotors = SoldMotor::with(['motor', 'warna'])->orderBy('tanggal_terjual', 'desc')->get();
        $soldSpareParts = SoldSparePart::with('sparePart')->orderBy('tanggal_terjual', 'desc')->get();

        return view('layouts.stock.sold-items', compact('soldMotors', 'soldSpareParts'));
    }
}
