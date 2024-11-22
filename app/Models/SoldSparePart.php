<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldSparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'jumlah',
        'harga_jual',
        'tanggal_terjual',
    ];

    public function order()
    {
        return $this->belongsTo(OrderMotor::class);
    }

    public function sparePart()
    {
        return $this->belongsTo(MasterSparePart::class, 'spare_part_id');
    }
}