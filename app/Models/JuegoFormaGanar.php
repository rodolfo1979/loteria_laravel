<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class JuegoFormaGanar extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "juegos_formas_ganar";
    protected $primaryKey = "juego_forma_ganar_id";
    protected $fillable =
        [
            "juego_id",
            "modalidad",
            "ejemplo",
            "premio_veces",
            "calculo_jugada_id",
            "juego_forma_ganar_padre_id",
            "orden_listado",
            "activo",
            "eliminado",
            "usuario_crea_id",
            "usuario_actualiza_id"
        ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";

}
