<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /** @use HasFactory<\Database\Factories\AttachmentFactory> */
    use HasFactory;
    protected $fillable = [
        'message_id',
        'path_id',
        'type',
    ];
    protected $casts = [
        'message_id' => 'integer',
        'path_id' => 'string',
        'type' => 'string',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
