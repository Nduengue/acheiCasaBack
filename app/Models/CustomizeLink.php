<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizeLink extends Model
{
    /** @use HasFactory<\Database\Factories\CustomizeLinkFactory> */
    use HasFactory;
    protected $fillable = [
        'agency_id',
        'intermediary_id',
        'user_id',
        'property_id',
        'link',
        'percent',
        'amount',
        'deleted'
    ];
    protected $casts = [
        'deleted' => 'boolean',
        'percent' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
    public function intermediary()
    {
        return $this->belongsTo(Intermediary::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
