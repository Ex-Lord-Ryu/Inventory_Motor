<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSparePart extends Model
{
    protected $fillable = [
        'user_id',
        'spare_part_id',
        'jumlah',
        'harga_jual',
        'tanggal_terjual',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sparePart()
    {
        return $this->belongsTo(MasterSparePart::class, 'spare_part_id');
    }
}