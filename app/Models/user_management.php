<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_management extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = ['role'];
}
