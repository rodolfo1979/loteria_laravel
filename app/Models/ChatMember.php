<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMember extends Model
{
    use HasUuids;

    protected $fillable = ['tenant_id', 'chat_id', 'profile_id', 'role', 'joined_at'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
