<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparison extends Model
{
    /** @use HasFactory<\Database\Factories\ComparisonFactory> */
    use HasFactory;
    /* Table comparisons {
        id serial [primary key]
        user_id int [ref: > user.id]
        property_id int [ref: > property.id]
        created_at timestamp [default: "now()"]
        deleted bool [default: false]
    } */
   protected $fillable = [
        'user_id',
        'property_id',
        'deleted',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function scopeDeleted($query)
    {
        return $query->where('deleted', true);
    }
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', false);
    }
    public function scopeWithUser($query)
    {
        return $query->with('user');
    }
    public function scopeWithProperty($query)
    {
        return $query->with('property');
    }
}
