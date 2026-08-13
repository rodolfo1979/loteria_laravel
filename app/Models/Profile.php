<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'email',
        'phone',
        'password',
        'full_name',
        'admin_alias',
        'admin_tags',
        'avatar_url',
        'role',
        'status',
        'last_seen_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'admin_tags' => 'array',
        'last_seen_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

