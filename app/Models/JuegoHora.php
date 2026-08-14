<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class JuegoHora extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "juegos_horas";
    protected $primaryKey = "juego_hora_id";
    protected $fillable =
        [
            "juego_id",
            "alias",
            "hora",
            "activo",
            "eliminado",
            "usuario_crea_id",
            "usuario_actualiza_id"
        ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";

}
