<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersDetails;
use App\Models\Distributor;
use App\Models\MasterMotor;
use App\Models\MasterSparePart;
use App\Models\MasterWarna;
use App\Models\StockMotor;
use App\Models\StockSparePart;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\OrderMotor;
use App\Models\OrderSparePart;
use App\Models\SoldMotor;
use App\Models\SoldSparePart;
use App\Models\User;

class PurchaseOrderSeeder extends Seeder
{
    private $motorDetails = [
        'Honda Vario 125 CBS' => ['JMD1E', 'MH1JMD1', 22500000, 17300000],
        'Honda Vario 125 CBS ISS' => ['JMD1E', 'MH1JMD1', 24200000, 18600000],
        'Honda Vario 125 CBS ISS SP' => ['JMD1E', 'MH1JMD1', 24500000, 18850000],
        'Honda Scoopy Sporty' => ['JMH1E', 'MH1JMH1', 22500000, 17300000],
        'Honda Scoopy Fashion' => ['JMH1E', 'MH1JMH1', 22500000, 17300000],
        'Honda Scoopy Stylish' => ['JMH1E', 'MH1JMH1', 23300000, 17900000],
        'Honda Scoopy Prestige' => ['JMH1E', 'MH1JMH1', 23300000, 17900000],
        'Honda Beat CBS' => ['JMG1E', 'MH1JMG1', 18400000, 14150000],
        'Honda Beat Deluxe' => ['JMG1E', 'MH1JMG1', 19300000, 14850000],
        'Honda Beat Smart Key' => ['JMG1E', 'MH1JMG1', 19800000, 15250000],
        'Honda PCX160 CBS' => ['KFE1E', 'MH1KFE1', 32700000, 25150000],
        'Honda PCX160 ABS' => ['KFE1E', 'MH1KFE1', 36000000, 27700000],
        'Honda Vario 160 CBS' => ['KFA1E', 'MH1KFA1', 26600000, 20450000],
        'Honda Vario 160 CBS Grande' => ['KFA1E', 'MH1KFA1', 27600000, 21250000],
        'Honda Vario 160 ABS' => ['KFA1E', 'MH1KFA1', 29500000, 22700000],
        'Honda Beat Street CBS' => ['JFZ2E', 'MH1JFZ2', 19300000, 14850000],
        'Honda Stylo 160 CBS' => ['KFD1E', 'MH1KFD11', 28000000, 21550000],
        'Honda Stylo 160 ABS' => ['KFC1E', 'MH1KFC11', 31000000, 23850000],
        'Honda CRF150L Standard' => ['KD11E1', 'MH1KD1', 35900000, 27600000],
        'Honda ADV 160 CBS' => ['KFB1E', 'MH1KFB1', 36200000, 27850000],
        'Honda ADV 160 ABS' => ['KFB2E', 'MH1KFB2', 39400000, 30300000],
        'Honda Genio CBS' => ['JMA1E', 'MH1JMB1', 19100000, 14700000],
        'Honda Genio CBS ISS' => ['JMA1E', 'MH1JMA1', 19700000, 15150000],
        'Honda Sonic 150R Racing Red' => ['KB11E', 'MH1KB11', 25200000, 19400000],
        'Honda Supra X 125 FI Spoke FI' => ['JB4*E', 'MH1JB4', 25400000, 19550000],
        'Honda Supra X 125 FI CW Luxury' => ['JB5*E', 'MH1JB5', 25900000, 19950000],
        'Honda CBR250RR Standard' => ['MC71E', 'MH1MC71', 63500000, 48850000],
        'Honda CBR250RR SP' => ['MC82E', 'MH1MC82', 75700000, 58250000],
        'Honda CBR150R Standard' => ['KCB1E', 'MH1KCB1', 37300000, 28700000],
        'Honda CBR150R Racing Red Standard' => ['KCC1E', 'MH1KCC1', 38000000, 29250000],
        'Honda CBR150R MotoGP Edition ABS' => ['KCB1E', 'MH1KCB2', 41700000, 32100000],
        'Honda CBR150R Racing Red ABS' => ['KCC1E', 'MH1KCC2', 41500000, 31950000],
        'Honda CBR150R ABS' => ['KCB1E', 'MH1KCB3', 41400000, 31850000],
        'Honda Revo Fit' => ['JBK1E', 'MH1JBK1', 16000000, 12300000],
        'Honda Revo X' => ['JBK2E', 'MH1JBK2', 17700000, 13600000],
        'Honda CB150 Verza Spoke' => ['KC51E', 'MH1KC511', 20900000, 16100000],
        'Honda CB150 Verza CW' => ['KC51E', 'MH1KC511', 21600000, 16600000],
        'Honda CB150R Streetfire Standard' => ['KCC1E', 'MH1KCC2', 30700000, 23600000],
        'Honda CB150R Streetfire Special Edition' => ['KCB1E', 'MH1KCB3', 31500000, 24250000],
        'Honda CB150X Standard' => ['KCE1E', 'MH1KCE1', 33900000, 26100000],
        'Honda Forza 250 Standard' => ['MD38E', 'MLHMD44', 90300000, 69450000],
        'Honda CRF250Rally Standard' => ['MD38E', 'MLHMD44', 92900000, 71450000],
        'Honda CB650R Standard' => ['RC74E', 'MLHRC7', 145000000, 111550000],
        'Honda CRF250L Standard' => ['MD38E', 'MLHMD44', 79900000, 61450000],
        'Honda EM1 E Electric' => ['EF22E', 'MH1EF22', 40000000, 30750000],
        'Honda EM1 E Plus' => ['EF21E', 'MH1EF21', 40500000, 31150000],
        'Honda CB500X Standard' => ['PC44E', 'MLHPC46', 135000000, 103850000],
        'Honda Monkey Standard' => ['JB03E', 'MLHJB039', 125000000, 96150000],
        'Honda Super Cub 125 Standard' => ['JA48E', 'MLHJA48', 83000000, 63850000],
        'Honda CT125 Standard' => ['JA55E', 'MLHJA559', 77200000, 59400000],
        'Honda ST125 Dax Standard' => ['JB04E', 'MLHJB049', 81400000, 62600000],
        'Honda Goldwing Standard' => ['SC47E', 'MLHSC47', 150000000, 115400000],
        'Honda XL750 Transalp Standard' => ['RC64E', 'MLHRC64', 145000000, 111550000],
        'Honda Rebel Standard' => ['RC71E', 'MLHRC71', 149500000, 115000000],
        'Honda Rebel 1100 Standard' => ['RC87E', 'MLHRC87', 148000000, 113850000],
        'Honda Supra GTR 150 Sporty' => ['KC56E', 'MH1KC56', 24500000, 18850000],
        'Honda Supra GTR 150 Exclusive' => ['KC56E', 'MH1KC56', 25500000, 19600000],
        'Honda CBR1000RR-R STD' => ['SC82E', 'MLHSC82', 149900000, 115300000],
        'Honda CRF1100L Africa Twin Manual' => ['SD09E', 'MLHSD09', 135000000, 103850000],
        'Honda CRF1100L Africa Twin DCT' => ['SD10E', 'MLHSD10', 145000000, 111550000],
    ];

    private $sparePartDetails = [
        'Busi' => [500000, 650000],
        'Kampas Rem Depan' => [600000, 780000],
        'Oli Mesin' => [700000, 910000],
        'Filter Udara' => [500000, 650000],
        'Rantai' => [800000, 1040000],
        'Aki' => [1000000, 1300000],
        'Lampu Depan' => [550000, 715000],
        'Shockbreaker Belakang' => [1200000, 1560000],
        'Kampas Kopling' => [700000, 910000],
        'Karburator' => [1500000, 1950000],
        'CDI' => [900000, 1170000],
        'Filter Oli' => [500000, 650000],
        'Ban Depan' => [1000000, 1300000],
        'Kampas Rem Belakang' => [600000, 780000],
        'Kick Starter' => [750000, 975000],
        'Gir Depan' => [500000, 650000],
        'Gir Belakang' => [500000, 650000],
        'Kabel Gas' => [550000, 715000],
        'Tutup Tangki Bensin' => [500000, 650000],
        'Spakbor Depan' => [800000, 1040000],
        'Bearing Roda Depan' => [600000, 780000],
        'Radiator' => [2000000, 2600000],
        'Speedometer' => [1500000, 1950000],
        'Tali Rem Depan' => [500000, 650000],
        'Karet Footstep' => [500000, 650000],
        'Pegangan Setang' => [600000, 780000],
        'Tutup Radiator' => [500000, 650000],
        'Tutup Oli Mesin' => [500000, 650000],
        'Engkol Starter' => [750000, 975000],
        'Cover Mesin' => [1500000, 1950000]
    ];

    public function run()
    {
        $faker = Faker::create();

        $distributorIds = Distributor::pluck('id')->toArray();
        $motorIds = MasterMotor::pluck('id', 'nama_motor')->toArray();
        $sparePartIds = MasterSparePart::pluck('id')->toArray();
        $warnaIds = MasterWarna::pluck('id')->toArray();

        if (empty($distributorIds)) {
            $this->command->error('No distributors found. Please run DistributorSeeder first.');
            return;
        }

        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::create(2024, 12, 31);

        for ($i = 0; $i < 50; $i++) {
            $purchaseDate = $faker->dateTimeBetween($startDate, $endDate);
            $status = $faker->randomElement(['pending', 'cancelled', 'completed']);
            $invoice = 'INV-' . Carbon::parse($purchaseDate)->format('Y') . '-' . time() . '-' . ($i + 1);

            $purchaseOrder = PurchaseOrder::create([
                'invoice' => $invoice,
                'vendor_id' => $faker->randomElement($distributorIds),
                'status' => $status,
                'order' => $i + 1,
                'created_at' => $purchaseDate,
                'updated_at' => $purchaseDate,
            ]);

            $detailCount = $faker->numberBetween(1, 5);
            for ($j = 0; $j < $detailCount; $j++) {
                $isMotor = $faker->boolean;
                if ($isMotor && !empty($motorIds)) {
                    $motorName = $faker->randomElement(array_keys($motorIds));
                    $motorId = $motorIds[$motorName];
                    $warnaId = !empty($warnaIds) ? $faker->randomElement($warnaIds) : null;
                    $sparePartId = null;
                    if (isset($this->motorDetails[$motorName])) {
                        $hargaBeli = $this->motorDetails[$motorName][3];
                        $hargaJual = $this->motorDetails[$motorName][2];
                    } else {
                        $hargaBeli = $faker->randomFloat(2, 10000000, 1000000000);
                        $hargaJual = $hargaBeli * 1.25; // 25% markup
                        $this->command->warn("Motor not found in motorDetails: $motorName. Using random prices.");
                    }
                } else {
                    $motorId = null;
                    $warnaId = null;
                    $sparePartId = !empty($sparePartIds) ? $faker->randomElement($sparePartIds) : null;
                    
                    if ($sparePartId) {
                        $sparePart = MasterSparePart::find($sparePartId);
                        if ($sparePart) {
                            if (isset($this->sparePartDetails[$sparePart->nama])) {
                                $hargaBeli = $this->sparePartDetails[$sparePart->nama][0];
                                $hargaJual = $this->sparePartDetails[$sparePart->nama][1];
                            } else {
                                $hargaBeli = $faker->randomFloat(2, 100000, 2000000);
                                $hargaJual = $hargaBeli * 1.3; // 30% markup
                            }
                        }
                    }
                }

                $jumlah = $faker->numberBetween(1, 10);
                $totalHarga = $jumlah * $hargaBeli;

                $detail = PurchaseOrdersDetails::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'motor_id' => $motorId,
                    'spare_part_id' => $sparePartId,
                    'warna_id' => $warnaId,
                    'jumlah' => $jumlah,
                    'harga' => $hargaBeli,
                    'total_harga' => $totalHarga,
                    'invoice' => $invoice . '-' . ($j + 1),
                    'status' => $status,
                    'order' => $j + 1,
                    'created_at' => $purchaseDate,
                    'updated_at' => $purchaseDate,
                ]);

                // Update stock for completed orders
                if ($status === 'completed') {
                    if ($isMotor && isset($this->motorDetails[$motorName])) {
                        $motorDetail = $this->motorDetails[$motorName];
                        for ($k = 0; $k < $jumlah; $k++) {
                            $nomorMesin = $motorDetail[0] . $faker->unique()->regexify('[0-9]{6}');
                            $nomorRangka = $motorDetail[1] . $faker->unique()->regexify('[A-Z0-9]{11}');

                            $stockMotor = StockMotor::create([
                                'purchase_order_detail_id' => $detail->id,
                                'motor_id' => $motorId,
                                'warna_id' => $warnaId,
                                'harga_beli' => $hargaBeli,
                                'harga_jual' => $hargaJual,
                                'nomor_rangka' => $nomorRangka,
                                'nomor_mesin' => $nomorMesin,
                                'type' => 'in',
                            ]);

                            // Create order and sold items for motors
                            if ($k < 25) { // Limit to 25 sales
                                $salesDate = $faker->dateTimeBetween($purchaseDate, $endDate);
                                
                                // Create order motor
                                $orderMotor = OrderMotor::create([
                                    'user_id' => User::inRandomOrder()->first()->id,
                                    'motor_id' => $motorId,
                                    'warna_id' => $warnaId,
                                    'jumlah' => 1,
                                    'nomor_rangka' => $nomorRangka,
                                    'nomor_mesin' => $nomorMesin,
                                    'harga_jual' => $hargaJual,
                                    'tanggal_terjual' => $salesDate,
                                ]);

                                // Create sold motor
                                SoldMotor::create([
                                    'motor_id' => $motorId,
                                    'warna_id' => $warnaId,
                                    'nomor_rangka' => $nomorRangka,
                                    'nomor_mesin' => $nomorMesin,
                                    'harga_jual' => $hargaJual,
                                    'tanggal_terjual' => $salesDate,
                                ]);

                                // Update stock motor type to 'out'
                                $stockMotor->update(['type' => 'out']);
                            }
                        }
                    } elseif (!$isMotor && $sparePartId) {
                        $stockSparePart = StockSparePart::create([
                            'purchase_order_detail_id' => $detail->id,
                            'spare_part_id' => $sparePartId,
                            'jumlah' => $jumlah,
                            'harga_beli' => $hargaBeli,
                            'harga_jual' => $hargaJual,
                            'type' => 'in',
                        ]);

                        // Create order and sold items for spare parts
                        if ($jumlah > 0) {
                            $salesQuantity = min(25, $jumlah); // Limit to 25 or available quantity
                            $salesDate = $faker->dateTimeBetween($purchaseDate, $endDate);

                            // Create order spare part
                            $orderSparePart = OrderSparePart::create([
                                'user_id' => User::inRandomOrder()->first()->id,
                                'spare_part_id' => $sparePartId,
                                'jumlah' => $salesQuantity,
                                'harga_jual' => $hargaJual,
                                'tanggal_terjual' => $salesDate,
                            ]);

                            // Create sold spare part
                            SoldSparePart::create([
                                'spare_part_id' => $sparePartId,
                                'jumlah' => $salesQuantity,
                                'harga_jual' => $hargaJual,
                                'tanggal_terjual' => $salesDate,
                            ]);

                            // Update stock spare part
                            $stockSparePart->update([
                                'jumlah' => $jumlah - $salesQuantity,
                                'type' => $jumlah - $salesQuantity > 0 ? 'in' : 'out'
                            ]);
                        }
                    }
                }
            }
        }
    }
}
