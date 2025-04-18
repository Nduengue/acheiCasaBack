<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentReceiptFactory> */
    use HasFactory;
    protected $fillable = [
        'business_id',
        'user_id',
        'payment_method',
        'amount',
        'status',
        'deleted'
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeActive($query)
    {
        return $query->where('deleted', false);
    }
}
