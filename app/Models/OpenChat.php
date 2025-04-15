<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenChat extends Model
{
    /** @use HasFactory<\Database\Factories\OpenChatFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'property_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'property_id' => 'integer',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    //Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class, 'chat_id')->latest();
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'chat_id')->where('read', false);
    }

    public function readMessages()
    {
        return $this->hasMany(Message::class, 'chat_id')->where('read', true);
    }
    
    public function deletedMessages()
    {
        return $this->hasMany(Message::class, 'chat_id')->where('deleted', true);
    }
}
