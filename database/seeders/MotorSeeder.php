<?php

namespace Database\Seeders;

use App\Models\MasterMotor;
use Illuminate\Database\Seeder;

class MotorSeeder extends Seeder
{
    public function run()
    {
        // Array of motors with their corresponding order
        $motor = [
            ['nama_motor' => 'Honda Vario 125 CBS', 'order' => 1],
            ['nama_motor' => 'Honda Vario 125 CBS ISS', 'order' => 2],
            ['nama_motor' => 'Honda Vario 125 CBS ISS SP', 'order' => 3],
            ['nama_motor' => 'Honda Scoopy Sporty', 'order' => 4],
            ['nama_motor' => 'Honda Scoopy Fashion', 'order' => 5],
            ['nama_motor' => 'Honda Scoopy Stylish', 'order' => 6],
            ['nama_motor' => 'Honda Scoopy Prestige', 'order' => 7],
            ['nama_motor' => 'Honda Beat CBS', 'order' => 8],
            ['nama_motor' => 'Honda Beat Deluxe', 'order' => 9],
            ['nama_motor' => 'Honda Beat Smart Key', 'order' => 10],
            ['nama_motor' => 'Honda PCX160 CBS', 'order' => 11],
            ['nama_motor' => 'Honda PCX160 ABS', 'order' => 12],
            ['nama_motor' => 'Honda Vario 160 CBS', 'order' => 13],
            ['nama_motor' => 'Honda Vario 160 CBS Grande', 'order' => 14],
            ['nama_motor' => 'Honda Vario 160 ABS', 'order' => 15],
            ['nama_motor' => 'Honda Beat Street CBS', 'order' => 16],
            ['nama_motor' => 'Honda Stylo 160 CBS', 'order' => 17],
            ['nama_motor' => 'Honda Stylo 160 ABS', 'order' => 18],
            ['nama_motor' => 'Honda CRF150L Standard', 'order' => 19],
            ['nama_motor' => 'Honda ADV 160 CBS', 'order' => 20],
            ['nama_motor' => 'Honda ADV 160 ABS', 'order' => 21],
            ['nama_motor' => 'Honda Genio CBS', 'order' => 22],
            ['nama_motor' => 'Honda Genio CBS ISS', 'order' => 23],
            ['nama_motor' => 'Honda Supra GTR 150 Sporty', 'order' => 24],
            ['nama_motor' => 'Honda Supra GTR 150 Exclusive', 'order' => 25],
            ['nama_motor' => 'Honda Sonic 150R Racing Red', 'order' => 26],
            ['nama_motor' => 'Honda Supra X 125 FI Spoke FI', 'order' => 27],
            ['nama_motor' => 'Honda Supra X 125 FI CW Luxury', 'order' => 28],
            ['nama_motor' => 'Honda CBR250RR Standard', 'order' => 29],
            ['nama_motor' => 'Honda CBR250RR SP', 'order' => 30],
            ['nama_motor' => 'Honda CBR150R Standard', 'order' => 31],
            ['nama_motor' => 'Honda CBR150R Racing Red Standard', 'order' => 32],
            ['nama_motor' => 'Honda CBR150R MotoGP Edition ABS', 'order' => 33],
            ['nama_motor' => 'Honda CBR150R Racing Red ABS', 'order' => 34],
            ['nama_motor' => 'Honda CBR150R ABS', 'order' => 35],
            ['nama_motor' => 'Honda Revo Fit', 'order' => 36],
            ['nama_motor' => 'Honda Revo X', 'order' => 37],
            ['nama_motor' => 'Honda CB150 Verza Spoke', 'order' => 38],
            ['nama_motor' => 'Honda CB150 Verza CW', 'order' => 39],
            ['nama_motor' => 'Honda CB150R Streetfire Standard', 'order' => 40],
            ['nama_motor' => 'Honda CB150R Streetfire Special Edition', 'order' => 41],
            ['nama_motor' => 'Honda CB150X Standard', 'order' => 42],
            ['nama_motor' => 'Honda Forza 250 Standard', 'order' => 43],
            ['nama_motor' => 'Honda CRF250Rally Standard', 'order' => 44],
            ['nama_motor' => 'Honda CB650R Standard', 'order' => 45],
            ['nama_motor' => 'Honda CRF250L Standard', 'order' => 46],
            ['nama_motor' => 'Honda EM1 E Electric', 'order' => 47],
            ['nama_motor' => 'Honda EM1 E Plus', 'order' => 48],
            ['nama_motor' => 'Honda Rebel Standard', 'order' => 49],
            ['nama_motor' => 'Honda CB500X Standard', 'order' => 50],
            ['nama_motor' => 'Honda Monkey Standard', 'order' => 51],
            ['nama_motor' => 'Honda Super Cub 125 Standard', 'order' => 52],
            ['nama_motor' => 'Honda CT125 Standard', 'order' => 53],
            ['nama_motor' => 'Honda CBR1000RR-R STD', 'order' => 54],
            ['nama_motor' => 'Honda ST125 Dax Standard', 'order' => 55],
            ['nama_motor' => 'Honda Goldwing Standard', 'order' => 56],
            ['nama_motor' => 'Honda CRF1100L Africa Twin Manual', 'order' => 57],
            ['nama_motor' => 'Honda CRF1100L Africa Twin DCT', 'order' => 58],
            ['nama_motor' => 'Honda XL750 Transalp Standard', 'order' => 59],
            ['nama_motor' => 'Honda Rebel 1100 Standard', 'order' => 60],
        ];

        foreach ($motor as $m) {
            MasterMotor::create($m);
        }
    }
}
