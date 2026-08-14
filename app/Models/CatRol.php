<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CatRol extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "cat_roles";
    protected $primaryKey = "cat_rol_id";

    protected $fillable = ["nombre",
        "descripcion",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id",
    ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";
}
