<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Loteria extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = "loterias";
    protected $primaryKey = "loteria_id";
    protected $fillable =
        [
            "nombre",
            "descripcion",
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
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $search = trim($search);
            return $query->where("lo.nombre", "ILIKE", "%$search%")
                ->orwhere("lo.descripcion", "ILIKE", "%$search%");
        }
    }

    public function scopeNotLoteriaId($query, $loteriaId): object|null
    {
        if ($loteriaId) {
            return $query->where("loteria_id", "<>", $loteriaId);
        }
        return null;
    }

}
