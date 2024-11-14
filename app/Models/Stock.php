<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_detail_id',
        'motor_id',
        'spare_part_id',
        'warna_id',
        'jumlah',
        'harga_beli',
        'harga_jual',
        'harga_jual_diskon',
        'nomor_rangka',
        'nomor_mesin',
        'type',
        'order',
        'diskon_persen',
        'diskon_nilai',
        'transaction_type'
    ];

    protected $nullable = ['nomor_rangka', 'nomor_mesin', 'harga_jual', 'harga_jual_diskon'];

    public function motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(MasterSparePart::class, 'spare_part_id');
    }

    public function warna()
    {
        return $this->belongsTo(MasterWarna::class, 'warna_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'purchase_order_detail_id');
    }

    public function motorUnits()
    {
        return $this->hasMany(MotorUnit::class);
    }
}
