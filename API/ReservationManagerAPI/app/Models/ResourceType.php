<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResourceType extends Model
{
    //use HasFactory;

    protected $table = 'resource_types';

    protected $fillable = [
        'id',
        'code',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'active'
    ];
}
