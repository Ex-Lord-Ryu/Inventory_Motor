<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSparePart extends Model
{
    protected $fillable = [
        'purchase_order_detail_id', 'spare_part_id', 'jumlah',
        'harga_beli', 'harga_jual', 'type'
    ];

    public function purchaseOrderDetail()
    {
        return $this->belongsTo(PurchaseOrdersDetails::class, 'purchase_order_detail_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(MasterSparePart::class);
    }
}