<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distributors = [
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR', 'alamat' => 'Jl. Kalimantan, Danau Indah, Kec. Cikarang Bar., Kabupaten Bekasi, Jawa Barat 17530'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR PLANT 3A CIKARANG', 'alamat' => 'P432+F34, Jl. Kalimantan, Gandamekar, Kec. Cikarang Bar., Kabupaten Bekasi, Jawa Barat 17530'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR SUNTER', 'alamat' => 'Jl. Laksda Jl. Yos Sudarso No.2, RW.9, Sunter Jaya, Kec. Tj. Priok, Jkt Utara, Daerah Khusus Ibukota Jakarta 14350'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR PLAN 2 PEGANGSAAN', 'alamat' => 'Astra Honda Motor Plant 2, Jalan Raya Pengangsaan KM.2,2 No.2, RW.3, Pegangsaan Dua, Kec. Klp. Gading, Jkt Utara, Daerah Khusus Ibukota Jakarta 14250'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR CIKARANG GATE 6', 'alamat' => 'Pt Astra Honda Motor, Jl. Kalimantan No.A1, Danau Indah, Kec. Cikarang Bar., Kabupaten Bekasi, Jawa Barat 17530'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR PLANT 3 CKR9', 'alamat' => 'P434+937, Danau Indah, Cikarang Barat, Bekasi Regency, West Java 17530'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR PLAN DMD', 'alamat' => 'Jl. Jarakosta No.64, RT.3/RW.1, Danau Indah, Kec. Cikarang Bar., Kabupaten Bekasi, Jawa Barat 17530'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR CENTRAL PRESS', 'alamat' => 'P442+3MC, Jl. Nasional 1, Danau Indah, Kec. Cikarang Bar., Kabupaten Bekasi, Jawa Barat 17530'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR MANUFACTURE', 'alamat' => 'Jalan Pulobuaran Raya Blok Ff No.1, RT.8/RW.13, Jatinegara, Cakung, RT.8/RW.13, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13930'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR KARAWANG PLANT 4', 'alamat' => 'HC7J+HFR, Kamojing, Cikampek, Karawang, West Java 41373'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR KARAWANG PLANT 5', 'alamat' => 'Kawasan Industri Indotaise Sektor 2 Blok A1, A2, B & C, Jl. Kota Bukit Indah Raya, Kalihurip, Kec. Cikampek, Karawang, Jawa Barat 41373'],
            ['name_vendor' => 'PT. ASTRA HONDA MOTOR PARTS CENTER', 'alamat' => 'Kawasan Industri Indotaise Sektor 2 Blok A1, A2, B & C, Kamojing, Kec. Cikampek, Karawang, Jawa Barat 41373'],
            ['name_vendor' => 'ASTRA MOTOR CENTER JAKARTA', 'alamat' => 'Jl. Dewi Sartika No.255, RT.8/RW.5, Cawang, Kec. Kramat jati, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13630'],
            ['name_vendor' => 'PT Astra International Tbk. – Honda Sales Operation ( Astra Motor ) Head Office', 'alamat' => 'Gedung AMDI B, Jl. Gaya Motor Raya No.8, RW.8, Bambu River, Tanjung Priok, North Jakarta City, Jakarta 14330'],
            ['name_vendor' => 'ASTRA HONDA MOTOR YAYASAN', 'alamat' => 'No.1, Jl. Yos Sudarso, RT.2/RW.9, Sunter Jaya, Kec. Tj. Priok, Jkt Utara, Daerah Khusus Ibukota Jakarta 14360'],
            ['name_vendor' => 'ASTRA HONDA DEALER', 'alamat' => 'Jl. Raya Mangga Besar No.132E, RW.1, Kartini, Kecamatan Sawah Besar, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10750'],
            ['name_vendor' => 'ASTRA HONDA BEKASI DEALER', 'alamat' => 'Jl. Caman Raya No.26, RT.008/RW.001, Jatibening, Kec. Pd. Gede, Kota Bks, Jawa Barat 17412']
        ];

        foreach ($distributors as $distributor) {
            $distributor['telepon'] = '+62' . $this->generateRandomNumber(10);
            DB::table('distributors')->insert($distributor);
        }
    }

    private function generateRandomNumber($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }
}