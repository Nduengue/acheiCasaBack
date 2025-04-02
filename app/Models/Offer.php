<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    /** @use HasFactory<\Database\Factories\OfferFactory> */
    use HasFactory;
    protected $fillable = [
        'property_id',
        'offer_option_id',
        'deleted',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function offerOption()
    {
        return $this->belongsTo(OfferOption::class);
    }
}
