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
        'category_id',
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
        'announces',
        'deleted',
    ];
    protected $casts = [
        'location' => 'array',
        'announces' => 'boolean',
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offer()
    {
        return $this->hasMany(Offer::class);
    }
    public function accommodationPhoto()
    {
        return $this->hasMany(AccommodationPhoto::class);
    }
    public function contact()
    {
        return $this->hasMany(Contact::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getPhotoPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
