<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CatAccion extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "cat_acciones";
    protected $primaryKey = "cat_accion_id";
    public $timestamps = false;

    protected $fillable = ["nombre",
        "activo",
    ];

    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";
}
