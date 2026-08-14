<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Agencia extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "agencias";
    protected $primaryKey = "agencia_id";
    protected $fillable =
    [
        "nombre_comercial",
        "razon_social",
        "numero_ruc",
        "lema",
        "email",
        "telefonos",
        "direccion",
        "email",
        "logo",
        "activo",
        "eliminado",
        "usuario_crea_id",
        "usuario_actualiza_id"
    ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";

    ///queryScope
    public function scopeBusqueda($query, $busqueda){
        if($busqueda) {
            return $query->where("ti.nombre_comercial", "like", "%$busqueda%")
                ->orwhere("ti.razon_social", "like", "%$busqueda%")
                ->orwhere("ti.numero_ruc", "like", "%$busqueda%")
                ->orwhere("ti.email", "like", "%$busqueda%")
                ->orwhere("ti.direccion", "like", "%$busqueda%");
        }
    }

}
