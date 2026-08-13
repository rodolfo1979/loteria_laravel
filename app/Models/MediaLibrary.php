<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    use HasUuids;

    protected $table = 'media_library';

    protected $fillable = [
        'tenant_id',
        'title',
        'tag',
        'image_url',
    ];
}

