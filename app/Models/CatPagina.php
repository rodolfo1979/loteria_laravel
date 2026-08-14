<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CatPagina extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "cat_paginas";
    protected $primaryKey = "cat_pagina_id";
    protected $fillable = ["nombre",
        "slug",
        "icono",
        "es_menu",
        "cat_padre_id",
        "orden",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id",
    ];

    public $timestamps = false;

    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";
}
