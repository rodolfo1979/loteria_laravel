<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanCatalog extends Model
{
    protected $table = 'plan_catalog';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'user_limit',
        'features',
        'monthly_price',
        'active',
    ];

    protected $casts = [
        'features' => 'array',
        'active' => 'boolean',
        'user_limit' => 'integer',
        'monthly_price' => 'integer',
    ];
}

