<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentReceiptFactory> */
    use HasFactory;
    /*  Table payment_receipts {
        id serial [primary key]
        business_id int [ref: > businesses.id]
        user_id int [ref: > user.id, note: "quem enviou o comprovativo"] 
        path_receipt varchar [note: "caminho do comprovativo (imagem ou pdf)"]
        payment_method enum("bank_transfer", "cash", "other") [default: "bank_transfer"]
        sent_at timestamp 
        approved bool [default: false, note: "Aprovado manualmente?"]
        notes text [null]
    } */
    protected $fillable = [
        'business_id',
        'user_id',
        'path_receipt',
        'payment_method',
        'sent_at',
        'approved',
        'notes',
    ];
    protected $casts = [
        'sent_at' => 'datetime',
        'approved' => 'boolean',
    ];
    protected $attributes = [
        'approved' => false,
    ];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getPathReceiptAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
