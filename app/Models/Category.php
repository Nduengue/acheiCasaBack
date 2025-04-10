<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'icon',
        'title',
        'deleted'
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
