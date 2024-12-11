<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $fillable = [
        'id',
        'code',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'active'
    ];
}
