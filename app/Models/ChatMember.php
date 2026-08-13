<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    use HasUuids;

    protected $fillable = ['tenant_id', 'chat_id', 'profile_id', 'role', 'joined_at'];
}

