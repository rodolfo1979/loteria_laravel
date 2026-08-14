<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class RolPaginaAccion extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "roles_paginas_acciones";
    protected $primaryKey = "rol_pagina_accion_id";

    protected $fillable = ["cat_rol_id",
        "pagina_accion_id",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id",
    ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";
}
