<?php

namespace App\Models;

use App\Models\PurchaseOrdersDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['invoice', 'vendor_id', 'status', 'order'];

    // Relationship with Vendor (Distributor)
    public function vendor()
    {
        return $this->belongsTo(Distributor::class, 'vendor_id');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrdersDetails::class, 'purchase_order_id', 'id');
    }
}
