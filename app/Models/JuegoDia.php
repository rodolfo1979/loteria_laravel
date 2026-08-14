<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class JuegoDia extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "juegos_dias";
    protected $primaryKey = "juego_id";
    protected $fillable =
        [
            "loteria_id",
            "nombre",
            "descripcion",
            "logo",
            "mecanismo_juego_id",
            "activo",
            "eliminado",
            "usuario_crea_id",
            "usuario_actualiza_id"
        ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";

}
