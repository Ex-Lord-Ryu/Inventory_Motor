<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMotor extends Model
{
    protected $fillable = [
        'purchase_order_detail_id', 'motor_id', 'warna_id', 'jumlah',
        'harga_beli', 'harga_jual', 'nomor_rangka', 'nomor_mesin', 'type'
    ];

    public function purchaseOrderDetail()
    {
        return $this->belongsTo(PurchaseOrdersDetails::class, 'purchase_order_detail_id');
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