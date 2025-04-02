<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;
    protected $fillable = [
        'chat_id',
        'sender_id',
        'content',
        'sent_in',
        'read',
        'deleted'
    ];
    protected $casts = [
        'sent_in' => 'datetime',
        'read' => 'boolean',
        'deleted' => 'boolean'
    ];
    protected $attributes = [
        'read' => false,
        'deleted' => false
    ];
    public function chat()
    {
        return $this->belongsTo(OpenChat::class, 'chat_id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }
    public function scopeDeleted($query)
    {
        return $query->where('deleted', true);
    }
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', false);
    }
    public function scopeSent($query)
    {
        return $query->where('sender_id', Auth::user()->id());
    }
    public function scopeReceived($query)
    {
        return $query->where('sender_id', '!=', Auth::user()->id());
    }
    public function scopeByChat($query, $chatId)
    {
        return $query->where('chat_id', $chatId);
    }
}
