<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrdersDetails extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'purchase_order_id',
        'invoice',
        'motor_id',
        'spare_part_id',
        'jumlah',
        'harga',
        'total_harga',
        'order',
        'status',
        'warna_id',
    ];
    

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->total_harga = $model->jumlah * $model->harga;
        });
    }   
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }    

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

}
