<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class MiSession extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "mis_sessions";
    protected $primaryKey = "id";

    public $timestamps = false;

}
