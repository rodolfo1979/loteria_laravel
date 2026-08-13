<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LotteryResult extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'lottery_draw_id',
        'result_date',
        'winning_numbers',
        'source',
        'published_at',
    ];

    protected $casts = [
        'result_date' => 'date',
        'winning_numbers' => 'array',
        'published_at' => 'datetime',
    ];

    public function draw(): BelongsTo
    {
        return $this->belongsTo(LotteryDraw::class, 'lottery_draw_id');
    }
}

