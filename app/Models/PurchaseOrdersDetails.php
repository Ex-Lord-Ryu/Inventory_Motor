<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrdersDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id',
        'invoice',
        'motor_id',
        'spare_part_id'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(MasterSparePart::class, 'spare_part_id');
    }
}
