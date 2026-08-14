<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CatMoneda extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "cat_monedas";
    protected $primaryKey = "cat_moneda_id";
    protected $fillable = ["nombre",
        "nombre_plural",
        "simbolo",
        "codigo_ISO",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id"
    ];

    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";
}
