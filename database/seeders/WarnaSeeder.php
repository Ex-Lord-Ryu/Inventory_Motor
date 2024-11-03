<?php

namespace Database\Seeders;

use App\Models\MasterWarna;
use Illuminate\Database\Seeder;

class WarnaSeeder extends Seeder
{
    public function run()
    {
        $warna = [
            ['id' => 'clr_black', 'nama_warna' => 'Black'],
            ['id' => 'clr_red', 'nama_warna' => 'Red'],
            ['id' => 'clr_matte_black', 'nama_warna' => 'Matte Black'],
            ['id' => 'clr_white', 'nama_warna' => 'White'],
            ['id' => 'clr_silver', 'nama_warna' => 'Silver'],
            ['id' => 'clr_blue', 'nama_warna' => 'Blue'],
            ['id' => 'clr_matte_blue', 'nama_warna' => 'Matte Blue'],
            ['id' => 'clr_green', 'nama_warna' => 'Green'],
            ['id' => 'clr_yellow', 'nama_warna' => 'Yellow'],
            ['id' => 'clr_orange', 'nama_warna' => 'Orange'],
            ['id' => 'clr_pearl_white', 'nama_warna' => 'Pearl White'],
            ['id' => 'clr_repsol_edition', 'nama_warna' => 'Repsol Edition'],
            ['id' => 'clr_racing_red', 'nama_warna' => 'Racing Red'],
            ['id' => 'clr_matte_brown', 'nama_warna' => 'Matte Brown'],
            ['id' => 'clr_prestige_silver', 'nama_warna' => 'Prestige Silver'],
            ['id' => 'clr_candy_red', 'nama_warna' => 'Candy Red'],
            ['id' => 'clr_matte_green', 'nama_warna' => 'Matte Green'],
            ['id' => 'clr_glossy_blue', 'nama_warna' => 'Glossy Blue'],
            ['id' => 'clr_dark_green_matte', 'nama_warna' => 'Dark Green Matte'],
            ['id' => 'clr_urban_titanium', 'nama_warna' => 'Urban Titanium'],
            ['id' => 'clr_pearl_nightfall_blue', 'nama_warna' => 'Pearl Nightfall Blue'],
            ['id' => 'clr_matte_gunpowder_black', 'nama_warna' => 'Matte Gunpowder Black'],
        ];

        foreach ($warna as $color) {
            MasterWarna::create($color);
        }
    }
}
