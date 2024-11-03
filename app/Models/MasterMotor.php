<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMotor extends Model
{
    use HasFactory;

    protected $table = 'master_motors';

    protected $fillable = [
        'nama_motor',
        'order',
    ];

}
