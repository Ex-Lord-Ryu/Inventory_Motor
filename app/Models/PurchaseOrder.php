<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['invoice', 'vendor_id', 'status', 'order'];

    // Relationship with Vendor (Distributor)
    public function vendor()
    {
        return $this->belongsTo(Distributor::class, 'vendor_id');
    }
}
