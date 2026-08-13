<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChatReadMarker extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'chat_id',
        'profile_id',
        'last_read_message_at',
    ];

    protected $casts = [
        'last_read_message_at' => 'datetime',
    ];
}

