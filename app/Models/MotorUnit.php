<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorUnit extends Model
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_SOLD = 'sold';

    protected $fillable = ['stock_id', 'nomor_rangka', 'nomor_mesin', 'status'];

    public function stockMotor()
    {
        return $this->belongsTo(StockMotor::class);
    }
    
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    public function markAsSold()
    {
        $this->update(['status' => self::STATUS_SOLD]);
    }

    public function markAsAvailable()
    {
        $this->update(['status' => self::STATUS_AVAILABLE]);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeSold($query)
    {
        return $query->where('status', self::STATUS_SOLD);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($motorUnit) {
            $motorUnit->stock->increment('unit_count');
        });

        static::deleted(function ($motorUnit) {
            $motorUnit->stock->decrement('unit_count');
        });
    }
}