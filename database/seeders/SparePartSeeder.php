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
            ['nama_spare_part' => 'Busi'],
            ['nama_spare_part' => 'Kampas Rem Depan'],
            ['nama_spare_part' => 'Oli Mesin'],
            ['nama_spare_part' => 'Filter Udara'],
            ['nama_spare_part' => 'Rantai'],
            ['nama_spare_part' => 'Aki'],
            ['nama_spare_part' => 'Lampu Depan'],
            ['nama_spare_part' => 'Shockbreaker Belakang'],
            ['nama_spare_part' => 'Kampas Kopling'],
            ['nama_spare_part' => 'Karburator'],
            ['nama_spare_part' => 'CDI'],
            ['nama_spare_part' => 'Filter Oli'],
            ['nama_spare_part' => 'Ban Depan'],
            ['nama_spare_part' => 'Kampas Rem Belakang'],
            ['nama_spare_part' => 'Kick Starter'],
            ['nama_spare_part' => 'Gir Depan'],
            ['nama_spare_part' => 'Gir Belakang'],
            ['nama_spare_part' => 'Kabel Gas'],
            ['nama_spare_part' => 'Tutup Tangki Bensin'],
            ['nama_spare_part' => 'Spakbor Depan'],
            ['nama_spare_part' => 'Bearing Roda Depan'],
            ['nama_spare_part' => 'Radiator'],
            ['nama_spare_part' => 'Speedometer'],
            ['nama_spare_part' => 'Tali Rem Depan'],
            ['nama_spare_part' => 'Karet Footstep'],
            ['nama_spare_part' => 'Pegangan Setang'],
            ['nama_spare_part' => 'Tutup Radiator'],
            ['nama_spare_part' => 'Tutup Oli Mesin'],
            ['nama_spare_part' => 'Engkol Starter'],
            ['nama_spare_part' => 'Cover Mesin'],
        ];

        foreach ($spareParts as $index => $part) {
            $part['order'] = $index + 1;
            MasterSparePart::create($part);
        }
    }
}
