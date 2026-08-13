<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'sender_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
