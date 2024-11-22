<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldMotor extends Model
{
    use HasFactory;

    protected $fillable = [
        'motor_id',
        'warna_id',
        'nomor_rangka',
        'nomor_mesin',
        'harga_jual',
        'tanggal_terjual',
    ];

    public function order()
    {
        return $this->belongsTo(OrderMotor::class);
    }

    public function motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id');
    }

    public function warna()
    {
        return $this->belongsTo(MasterWarna::class, 'warna_id');
    }
}
