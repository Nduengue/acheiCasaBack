<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    /** @use HasFactory<\Database\Factories\BusinessFactory> */
    use HasFactory;
    protected $fillable = [
        'property_id',
        'buyer_id',
        'seller_id',
        'intermediary_id',
        'price',
        'status',
        'type_of_business',
        'started_at',
        'closed_at',
        'notes',
        'deleted'
    ];
    protected $casts = [
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function intermediary()
    {
        return $this->belongsTo(User::class, 'intermediary_id');
    }
    public function scopeActive($query)
    {
        return $query->where('deleted', false);
    }
    public function scopeInactive($query)
    {
        return $query->where('deleted', true);
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    public function scopeByType($query, $type)
    {
        return $query->where('type_of_business', $type);
    }
    
}
