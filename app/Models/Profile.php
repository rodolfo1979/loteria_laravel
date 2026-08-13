<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Authenticatable
{
    use HasApiTokens;
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

    public function chatMemberships(): HasMany
    {
        return $this->hasMany(ChatMember::class);
    }
}
