<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LotteryNumberInventory extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'lottery_draw_id',
        'number',
        'label',
        'status',
        'reserved_by',
        'reserved_until',
        'metadata',
    ];

    protected $casts = [
        'reserved_until' => 'datetime',
        'metadata' => 'array',
    ];

    public function draw(): BelongsTo
    {
        return $this->belongsTo(LotteryDraw::class, 'lottery_draw_id');
    }
}

