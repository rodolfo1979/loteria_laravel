<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class VentaDetalle extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "ventas_detalles";
    protected $primaryKey = "venta_detalle_id";
    protected $fillable =
        [
            "numero",
            "venta_id",
            "hora",
            "juego_forma_ganar_id",
            "monto",
            "activo",
            "eliminado",
            "usuario_crea_id",
            "usuario_actualiza_id"
        ];

    public $timestamps = false;
    const CREATED_AT = "fecha_crea";
    const UPDATED_AT = "fecha_actualiza";

}
