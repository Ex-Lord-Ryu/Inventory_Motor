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

class PurchaseOrderSeeder extends Seeder
{
    private $motorDetails = [
        'Honda Vario 125 CBS' => ['JMD1E', 'MH1JMD1', 22550000, 18040000],
        'Honda Vario 125 CBS ISS' => ['JMD1E', 'MH1JMD1', 24200000, 19360000],
        'Honda Vario 125 CBS ISS SP' => ['JMD1E', 'MH1JMD1', 24450000, 19560000],
        'Honda Scoopy Sporty' => ['JMH1E', 'MH1JMH1', 22520000, 18016000],
        'Honda Scoopy Fashion' => ['JMH1E', 'MH1JMH1', 22520000, 18016000],
        'Honda Scoopy Stylish' => ['JMH1E', 'MH1JMH1', 23330000, 18664000],
        'Honda Scoopy Prestige' => ['JMH1E', 'MH1JMH1', 23330000, 18664000],
        'Honda Beat CBS' => ['JMG1E', 'MH1JMG1', 18430000, 14744000],
        'Honda Beat Deluxe' => ['JMG1E', 'MH1JMG1', 19300000, 15440000],
        'Honda Beat Smart Key' => ['JMG1E', 'MH1JMG1', 19830000, 15864000],
        'Honda PCX160 CBS' => ['KFE1E', 'MH1KFE1', 32670000, 26136000],
        'Honda PCX160 ABS' => ['KFE1E', 'MH1KFE1', 36080000, 28864000],
        'Honda Vario 160 CBS' => ['KFA1E', 'MH1KFA1', 26640000, 21312000],
        'Honda Vario 160 CBS Grande' => ['KFA1E', 'MH1KFA1', 27600000, 22080000],
        'Honda Vario 160 ABS' => ['KFA1E', 'MH1KFA1', 29510000, 23608000],
        'Honda Beat Street CBS' => ['JFZ2E', 'MH1JFZ2', 19300000, 15440000],
        'Honda Stylo 160 CBS' => ['KFD1E', 'MH1KFD11', 28040000, 22432000],
        'Honda Stylo 160 ABS' => ['KFC1E', 'MH1KFC11', 31040000, 24832000],
        'Honda CRF150L Standard' => ['KD11E1', 'MH1KD1', 35930000, 28744000],
        'Honda ADV 160 CBS' => ['KFB1E', 'MH1KFB1', 36200000, 28960000],
        'Honda ADV 160 ABS' => ['KFB2E', 'MH1KFB2', 39400000, 31520000],
        'Honda Genio CBS' => ['JMA1E', 'MH1JMB1', 19110000, 15288000],
        'Honda Genio CBS ISS' => ['JMA1E', 'MH1JMA1', 19730000, 15784000],
        'Honda Sonic 150R Racing Red' => ['KB11E', 'MH1KB11', 25180000, 20144000],
        'Honda Supra X 125 FI Spoke FI' => ['JB4*E', 'MH1JB4', 25430000, 20344000],
        'Honda Supra X 125 FI CW Luxury' => ['JB5*E', 'MH1JB5', 25920000, 20736000],
        'Honda CBR250RR Standard' => ['MC71E', 'MH1MC71', 63460000, 50768000],
        'Honda CBR250RR SP' => ['MC82E', 'MH1MC82', 75660000, 60528000],
        'Honda CBR150R Standard' => ['KCB1E', 'MH1KCB1', 37280000, 29824000],
        'Honda CBR150R Racing Red Standard' => ['KCC1E', 'MH1KCC1', 37990000, 30392000],
        'Honda CBR150R MotoGP Edition ABS' => ['KCB1E', 'MH1KCB2', 41720000, 33376000],
        'Honda CBR150R Racing Red ABS' => ['KCC1E', 'MH1KCC2', 41520000, 33216000],
        'Honda CBR150R ABS' => ['KCB1E', 'MH1KCB3', 41400000, 33120000],
        'Honda Revo Fit' => ['JBK1E', 'MH1JBK1', 16020000, 12816000],
        'Honda Revo X' => ['JBK2E', 'MH1JBK2', 17730000, 14184000],
        'Honda CB150 Verza Spoke' => ['KC51E', 'MH1KC511', 20940000, 16752000],
        'Honda CB150 Verza CW' => ['KC51E', 'MH1KC511', 21600000, 17280000],
        'Honda CB150R Streetfire Standard' => ['KCC1E', 'MH1KCC2', 30660000, 24528000],
        'Honda CB150R Streetfire Special Edition' => ['KCB1E', 'MH1KCB3', 31530000, 25224000],
        'Honda CB150X Standard' => ['KCE1E', 'MH1KCE1', 33910000, 27128000],
        'Honda Forza 250 Standard' => ['MD38E', 'MLHMD44', 90330000, 72264000],
        'Honda CRF250Rally Standard' => ['MD38E', 'MLHMD44', 92930000, 74344000],
        'Honda CB650R Standard' => ['RC74E', 'MLHRC7', 291010000, 232808000],
        'Honda CRF250L Standard' => ['MD38E', 'MLHMD44', 79920000, 63936000],
        'Honda EM1 E Electric' => ['EF22E', 'MH1EF22', 40000000, 32000000],
        'Honda EM1 E Plus' => ['EF21E', 'MH1EF21', 40500000, 32400000],
        'Honda CB500X Standard' => ['PC44E', 'MLHPC46', 201690000, 161352000],
        'Honda Monkey Standard' => ['JB03E', 'MLHJB039', 204510000, 163608000],
        'Honda Super Cub 125 Standard' => ['JA48E', 'MLHJA48', 82970000, 66376000],
        'Honda CT125 Standard' => ['JA55E', 'MLHJA559', 77160000, 61728000],
        'Honda ST125 Dax Standard' => ['JB04E', 'MLHJB049', 81400000, 65120000],
        'Honda Goldwing Standard' => ['SC47E', 'MLHSC47', 1068000000, 854400000],
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
                    $hargaBeli = $faker->randomFloat(2, 100000, 50000000);
                    $hargaJual = $hargaBeli * 1.2; // 20% markup
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

                            StockMotor::create([
                                'purchase_order_detail_id' => $detail->id,
                                'motor_id' => $motorId,
                                'warna_id' => $warnaId,
                                'harga_beli' => $hargaBeli,
                                'harga_jual' => $hargaJual,
                                'nomor_rangka' => $nomorRangka,
                                'nomor_mesin' => $nomorMesin,
                                'type' => 'in',
                            ]);
                        }
                    } elseif (!$isMotor && $sparePartId) {
                        StockSparePart::create([
                            'purchase_order_detail_id' => $detail->id,
                            'spare_part_id' => $sparePartId,
                            'jumlah' => $jumlah,
                            'harga_beli' => $hargaBeli,
                            'harga_jual' => $hargaJual,
                            'type' => 'in',
                        ]);
                    }
                }
            }
        }
    }
}
