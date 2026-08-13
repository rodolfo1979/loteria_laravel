<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'chat_id',
        'sender_id',
        'body',
        'message_type',
        'attachment_url',
        'attachment_name',
    ];
}

