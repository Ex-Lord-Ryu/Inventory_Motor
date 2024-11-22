<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMotor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'motor_id',
        'warna_id',
        'jumlah',
        'nomor_rangka',
        'nomor_mesin',
        'harga_jual',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
