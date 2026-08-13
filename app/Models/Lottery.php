<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lottery extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'country',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function draws(): HasMany
    {
        return $this->hasMany(LotteryDraw::class);
    }
}

