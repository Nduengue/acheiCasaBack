<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyUser extends Model
{
    /** @use HasFactory<\Database\Factories\AgencyUserFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'agency_id',
        'deleted',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
