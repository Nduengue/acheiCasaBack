<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckPoint extends Model
{
    /** @use HasFactory<\Database\Factories\CheckPointFactory> */
    use HasFactory;
    /* Table checkpoints {
        id serial [primary key]
        user_id int [ref: > user.id]
        property_id int [ref: > property.id]
        check_in datatime 
        check_out datetime [not null, note: "opicional"]
        status enum("check_in","check_out","cancelled") [default: "check_in"]
    } */
    protected $fillable = [
        'user_id',
        'property_id',
        'check_in',
        'check_out',
        'status',
    ];
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function scopeCheckIn($query)
    {
        return $query->where('status', 'check_in');
    }
    public function scopeCheckOut($query)
    {
        return $query->where('status', 'check_out');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
