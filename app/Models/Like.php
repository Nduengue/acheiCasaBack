<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'property_id',
        'deleted',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', false);
    }
    public function scopeDeleted($query)
    {
        return $query->where('deleted', true);
    }
    public function scopeWithUser($query)
    {
        return $query->with('user');
    }
    public function scopeWithProperty($query)
    {
        return $query->with('property');
    }
    public function scopeWithUserAndProperty($query)
    {
        return $query->with(['user', 'property']);
    }
    
}
