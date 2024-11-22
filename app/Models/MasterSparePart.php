<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSparePart extends Model
{
    use HasFactory;

    protected $table = 'master_spare_parts';

    protected $fillable = [
        'nama_spare_part',
        'units_per_box',
        'order',
    ];
}
