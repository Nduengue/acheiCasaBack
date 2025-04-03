<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'path_id',
        'name',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'name' => 'string',
        'path_id' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
<<<<<<< HEAD
    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
    public function getNameAttribute($value)
    {
        return $value;
=======
    
    public function getPathIdAttribute(?string $value): ?string
    {
        return $value ? asset('storage/' . $value) : null;
>>>>>>> master
    }
}
