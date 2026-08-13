<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'title',
        'body',
        'active',
        'starts_at',
        'ends_at',
        'schedule',
    ];

    protected $casts = [
        'active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'schedule' => 'array',
    ];
}

