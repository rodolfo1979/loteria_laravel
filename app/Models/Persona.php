<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Persona extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "personas";
    protected $primaryKey = "persona_id";
    protected $fillable = [
        "numero_identidad",
        "nombres",
        "email",
        "celular",
        "direccion",
        "foto",
        "cat_tipo_persona_id",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id"];

    public $timestamps = false;

    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";


    public function scopeCatTipoPersonaId($query, $catTipoPersonaId)
    {
        $personaId = auth()->user()->persona_id;
        if ($catTipoPersonaId == 2 && $personaId == 1) {
            return $query->whereIn("pe.cat_tipo_persona_id", [$personaId, $catTipoPersonaId]);
        } else {
            return $query->where("pe.cat_tipo_persona_id", $catTipoPersonaId);
        }
    }

    ///buscador de listado
    public function scopeBusqueda($query, $busqueda): object|null
    {
        if ($busqueda) {
            return $query->where("pe.nombres", "like", "%$busqueda%")
                ->orwhere("pe.numero_identidad", "like", "%$busqueda%")
                ->orwhere("pe.direccion", "like", "%$busqueda%")
                ->orwhere("pe.celular", "like", "%$busqueda%")
                ->orwhere("pe.email", "like", "%$busqueda%");
        }
        return null;
    }

    // QUE EL VALOR SEA DIFERENTE AL ID
    public function scopeNotPersonaId($query, $personaId): object|null
    {
        if ($personaId) {
            return $query->where("persona_id", "<>", $personaId);
        }
        return null;
    }


}
