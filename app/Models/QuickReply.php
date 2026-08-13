<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class QuickReply extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'label',
        'tag',
        'body',
        'tag_color',
        'tag_emoji',
    ];
}

