<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AdminChatClear extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'chat_id',
        'admin_id',
        'cleared_at',
    ];

    protected $casts = [
        'cleared_at' => 'datetime',
    ];
}

