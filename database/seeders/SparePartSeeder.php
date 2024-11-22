<?php

namespace Database\Seeders;

use App\Models\MasterSparePart;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    public function run()
    {
        // Define the spare parts with their respective order
        $spareParts = [
            ['nama_spare_part' => 'Busi', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Kampas Rem Depan', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Oli Mesin', 'unit_satuan' => 12],
            ['nama_spare_part' => 'Filter Udara', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Rantai', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Aki', 'unit_satuan' => 5],
            ['nama_spare_part' => 'Lampu Depan', 'unit_satuan' => 20],
            ['nama_spare_part' => 'Shockbreaker Belakang', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Kampas Kopling', 'unit_satuan' => 20],
            ['nama_spare_part' => 'Karburator', 'unit_satuan' => 20],
            ['nama_spare_part' => 'CDI', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Filter Oli', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Ban Depan', 'unit_satuan' => 2],
            ['nama_spare_part' => 'Kampas Rem Belakang', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Kick Starter', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Gir Depan', 'unit_satuan' => 20],
            ['nama_spare_part' => 'Gir Belakang', 'unit_satuan' => 20],
            ['nama_spare_part' => 'Kabel Gas', 'unit_satuan' => 50],
            ['nama_spare_part' => 'Tutup Tangki Bensin', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Spakbor Depan', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Bearing Roda Depan', 'unit_satuan' => 30],
            ['nama_spare_part' => 'Radiator', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Speedometer', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Tali Rem Depan', 'unit_satuan' => 50],
            ['nama_spare_part' => 'Karet Footstep', 'unit_satuan' => 50],
            ['nama_spare_part' => 'Pegangan Setang', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Tutup Radiator', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Tutup Oli Mesin', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Engkol Starter', 'unit_satuan' => 10],
            ['nama_spare_part' => 'Cover Mesin', 'unit_satuan' => 10],
        ];

        foreach ($spareParts as $index => $part) {
            $part['order'] = $index + 1;
            MasterSparePart::create($part);
        }
    }
}
