<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory,SoftDeletes;

    protected $casts = [
        'coordinates' => 'array',
        'user_file_url' => 'array',
    ];
    protected $dates = ['deleted_at'];
}
