<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotteryDraw extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'lottery_id',
        'name',
        'draw_time',
        'timezone',
        'days_of_week',
        'status',
        'closes_before_minutes',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'closes_before_minutes' => 'integer',
    ];

    public function lottery(): BelongsTo
    {
        return $this->belongsTo(Lottery::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(LotteryResult::class);
    }
}

