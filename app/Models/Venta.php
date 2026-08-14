<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Venta extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "ventas";
    protected $primaryKey = "venta_id";
    protected $fillable =
        [
            "numero",
            "serie",
            "fecha_sorteo",
            "juego_id",
            "agencia_id",
            "cliente_id",
            "vendedor_id",
            "observacion",
            "comision_porcentaje",
            "total",
            "ip_address",
            "user_agent",
            "latitude",
            "longitude",
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
                ->orwhere("lo.nombre", "ILIKE", "%$search%")
                ->orwhere("ve.numero", "ILIKE", "%$search%")
                ->orwhere("ve.numero", "ILIKE", "%$search%")
                ->orwhere("cl.nombres", "ILIKE", "%$search%")
                ->orwhere("vnd.nombres", "ILIKE", "%$search%");
        }
    }

    /* @description FILTRAR POR JUEGO ID */
    public function scopeJuegoId($query, $loteriaId)
    {
        if ($loteriaId) {
            return $query->where("ve.juego_id", $loteriaId);
        }
    }

    /* @description QUE SEA DIFERENTE */
    public function scopeNotVentaId($query, $ventaId): object|null
    {
        if ($ventaId) {
            return $query->where("venta_id", "<>", $ventaId);
        }
        return null;
    }

}
