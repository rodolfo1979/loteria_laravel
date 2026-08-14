<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CatAtributo extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "cat_atributos";
    protected $primaryKey = "cat_atributo_id";
    protected $fillable = ["nombre",
        "aplicacion",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id"
    ];

    public function scopeBusqueda($query, $busqueda)
    {
        if ($busqueda) {
            return $query->where("at.nombre", "like", "%$busqueda%")
                ->orwhere("at.aplicacion", "like", "%$busqueda%");
        }
    }

    public function scopeCatAtributoIdDiferente($query, $catAtributoId)
    {
        if ($catAtributoId) {
            return $query->where("cat_atributo_id", "<>", $catAtributoId);
        }
    }

    public $timestamps = false;

    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";
}
