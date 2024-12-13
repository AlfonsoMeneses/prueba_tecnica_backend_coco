<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //
    use HasFactory;

    protected $table = 'resources';

    protected $fillable = [
        'id',
        'name',
        'description',
        'capacity',
        'resource_type_id' 
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'active'
    ];
}
