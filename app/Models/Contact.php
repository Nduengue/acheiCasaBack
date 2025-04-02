<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    protected $fillable = [
        'agency_id',
        'user_id',
        'property_id',
        'type',
        'value'
    ];
    protected $casts = [
        'agency_id' => 'integer',
        'user_id' => 'integer',
        'property_id' => 'integer',
        'type' => 'string',
        'value' => 'string'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
