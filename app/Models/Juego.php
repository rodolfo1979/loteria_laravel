<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Juego extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "juegos";
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

    /* @description FILTRAR POR BUSQUEDA */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $search = trim($search);
            return $query->where("ju.nombre", "ILIKE", "%$search%")
                ->orwhere("ju.descripcion", "ILIKE", "%$search%")
                ->orwhere("lo.nombre", "ILIKE", "%$search%");
        }
    }

    /* @description FILTRAR POR LOTERIA ID */
    public function scopeLoteriaId($query, $loteriaId)
    {
        if ($loteriaId) {
            return $query->where("ju.loteria_id", $loteriaId);
        }
    }

    /* @description QUE SEA DIFERENTE */
    public function scopeNotJuegoId($query, $juegoId): object|null
    {
        if ($juegoId) {
            return $query->where("juego_id", "<>", $juegoId);
        }
        return null;
    }

    /* @description QUE SEA IGUAL */
    public function scopeJuegoId($query, $juegoId): object|null
    {
        if ($juegoId) {
            return $query->where("juego_id", $juegoId);
        }
        return null;
    }

}
