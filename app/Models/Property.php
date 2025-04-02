<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'status',
        'type_of_business',
        'furnished',
        'country',
        'address',
        'city',
        'province',
        'location',
        'description',
        'room',
        'bathroom',
        'price',
    ];
    protected $casts = [
        'location' => 'array',
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
