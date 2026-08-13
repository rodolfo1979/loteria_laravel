<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'billing_status',
        'plan_code',
        'extra_user_slots',
        'logo_url',
        'primary_color',
        'secondary_color',
        'plan_started_at',
        'plan_expires_at',
        'ai_provider',
        'ai_image_provider',
    ];

    protected $casts = [
        'extra_user_slots' => 'integer',
        'plan_started_at' => 'datetime',
        'plan_expires_at' => 'datetime',
    ];

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}

