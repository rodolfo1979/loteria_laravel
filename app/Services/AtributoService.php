<?php

namespace App\Services;

use App\Models\CatAtributo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AtributoService
{

    public function getById($atributoId): array
    {
        $atributos = CatAtributo::from("cat_atributos AS ca")
            ->select(
                "ca.cat_atributo_id",
                "ca.valor",
                "ca.activo",
                "ca.aplicacion1",
                "ca.aplicacion2",
            )
            ->where("ca.eliminado", 0)
            ->when($atributoId, function ($query) use ($atributoId) {
                return $query->where("ca.cat_atributo_id", $atributoId);
            })
            ->orderBy("ca.valor")
            ->orderBy("ca.aplicacion1")
            ->get()->toArray();

        return json_decode(json_encode($atributos), true)[0];
    }

    // GET DATA
    public function getCatAtributos($aplicacion1 = null, $aplicacion2 = null, $todos = false): array
    {
        $atributos = DB::table("cat_atributos AS ca")
            ->select(
                "ca.cat_atributo_id",
                "ca.valor1",
                "ca.valor2",
            )
            ->where("ca.eliminado", false)
            ->where("ca.activo", true)
            ->when($aplicacion1, function ($query) use ($aplicacion1) {
                return $query->where("ca.aplicacion1", "LIKE", "%{$aplicacion1}%");
            })
            ->when($aplicacion2, function ($query) use ($aplicacion2) {
                return $query->where("ca.aplicacion2", "LIKE", "%{$aplicacion2}%");
            })
            ->orderBy("ca.valor1")
            ->orderBy("ca.valor2")
            ->orderBy("ca.aplicacion1")
            ->orderBy("ca.aplicacion2")
            ->get()->toArray();

        if ($todos) {
            array_unshift($atributos, ["cat_atributo_id" => null, "valor1" => "TODOS"]);
        }

        return $atributos;
    }

    // STORE DATA
    public function store($request)
    {
        try {

            if ($this->validarAtributo($request)) {
                return 0;
            }
            $atributo = new CatAtributo();
            $atributo->valor = $request->valor;
            $atributo->aplicacion1 = $request->aplicacion1;
            $atributo->aplicacion2 = $request->aplicacion2 ?? null;
            $atributo->usuario_crea_id = Auth::id();
            $atributo->save();

            return $atributo->cat_atributo_id;

        }
        catch (\Throwable $ex) {
            dd($ex);
        }
    }

    // UPDATE DATA
    public function update($request)
    {
        try {

            if ($this->validarAtributo($request)) {
                return 0;
            }

            $atributo = CatAtributo::findOrFail($request->cat_atributo_id);
            $atributo->valor = $request->valor;
            $atributo->aplicacion1 = $request->aplicacion1;
            if (isset($request->aplicacion2)) {
                $atributo->aplicacion2 = $request->aplicacion2;
            }
            if (isset($request->activo)) {
                $atributo->activo = $request->activo;
            }
            $atributo->usuario_actualiza_id = Auth::id();
            $atributo->fecha_actualiza = Carbon::now();
            $atributo->save();

            return $atributo->cat_atributo_id;

        }
        catch (\Throwable $ex) {
            dd($ex);
        }
    }

    // VALIDATE DATA
    private function validarAtributo($request): int
    {
        $valor = strtoupper(trim($request->valor));
        $aplicacion1 = strtoupper(trim($request->aplicacion1));

        $atributo = CatAtributo::select("cat_atributo_id")
            ->where("eliminado", false)
            ->whereRaw("UPPER(valor) = ?", [$valor])
            ->whereRaw("UPPER(aplicacion1) = ?", [$aplicacion1])
            ->whereNot("cat_atributo_id", $request->cat_atributo_id)
            ->get();

        return count($atributo);
    }

}
