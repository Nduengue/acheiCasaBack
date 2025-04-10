<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    protected $fillable = [
        'agency_id',
        'property_id',
        'type',
        'value'
    ];
    protected $casts = [
        'agency_id' => 'integer',
        'user_id' => 'integer',
        'property_id' => 'integer',
        'type' => 'string',
        'value' => 'string'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    // add id de user
    public function getUserIdAttribute($value)
    {
        return Auth::id();
    }
}
