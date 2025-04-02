<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intermediary extends Model
{
    /** @use HasFactory<\Database\Factories\IntermediaryFactory> */
    use HasFactory;
    protected $fillable = [
        'agency_id',
        'user_id',
        'accept',
        'deleted',
    ];
    protected $casts = [
        'accept' => 'boolean',
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'accept' => false,
        'deleted' => false,
    ];
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
