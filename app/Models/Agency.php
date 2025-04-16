<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    /** @use HasFactory<\Database\Factories\AgencyFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'path_photo',
        'code',
        'email',
        'address',
        'phone',
        'deleted',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agencyUsers()
    {
        return $this->hasMany(AgencyUser::class);
    }
<<<<<<< HEAD
    public function getPhotoPathAttribute($value)
=======

    public function getPathPhotoAttribute($value)
>>>>>>> 7db88f745e38833291986c08ce1eec79b7247268
    {
        return asset('storage/' . $value);
    }
}
