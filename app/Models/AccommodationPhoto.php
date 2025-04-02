<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationPhoto extends Model
{
    /** @use HasFactory<\Database\Factories\AccommodationPhotoFactory> */
    use HasFactory;
    protected $fillable = [
        'property_id',
        'photo_path',
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
    public function getPhotoPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
