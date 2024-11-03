<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterWarna extends Model
{
    use HasFactory;

    protected $table = 'master_warnas';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['id', 'nama_warna'];
}
