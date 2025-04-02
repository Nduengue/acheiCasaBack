<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferOption extends Model
{
    /** @use HasFactory<\Database\Factories\OfferOptionFactory> */
    use HasFactory;
    protected $fillable = [
        'property_id',
        'icon',
        'title',
        'deleted',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
