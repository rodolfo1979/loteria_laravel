<?php

namespace App\Services;

use App\Models\Loteria;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoteriaService
{
    private string $logoDefault = "images/loterias/logos/default.png";
    private string $logoPath = "images/loterias/logos";

    public function index($request)
    {
        $rowsPage = $request->filters["rowsPage"] ?? 15;
        $search = $request->filters["search"] ?? null;

        $loterias = Loteria::from("loterias AS lo")
            ->select(
                "lo.loteria_id",
                "lo.nombre",
                "lo.descripcion",
                "lo.logo",
                "lo.activo"
            )
            ->where("lo.eliminado", 0)
            ->search($search)
            ->orderBy("lo.nombre", "ASC")
            ->paginate($rowsPage);

        return [
            "pagination" => [
                "total" => $loterias->total(),
                "current_page" => $loterias->currentPage(),
                "per_page" => $loterias->perPage(),
                "last_page" => $loterias->lastPage(),
                "from" => $loterias->firstItem(),
                "to" => $loterias->lastItem(),
            ],
            "result" => $loterias
        ];
    }

    /* @description STORE */
    public function store($request)
    {
        $loteria = new Loteria();
        $loteria->nombre = $request->nombre;
        $loteria->descripcion = $request->descripcion ?? null;
        $loteria->logo = $this->logoDefault;
        $loteria->usuario_crea_id = Auth::id();
        $loteria->save();
        // ACTUALIZAR LA FOTO
        $this->saveLogo($request, $loteria);

        return $loteria;
    }

    /* @description STORE */
    public function update($request)
    {

        $loteria = Loteria::findOrFail($request->loteria_id);
        $loteria->nombre = $request->nombre;
        $loteria->descripcion = $request->descripcion ?? null;
        $loteria->activo = $request->activo ?? true;
        $loteria->usuario_actualiza_id = Auth::id();
        $loteria->fecha_actualiza = Carbon::now();
        $loteria->save();

        // ACTUALIZAR LA FOTO
        $this->saveLogo($request, $loteria);

        return $loteria;
    }

    public function edit($request)
    {

        $loteria = Loteria::from("loterias AS lo")
            ->select("lo.loteria_id",
                "lo.nombre",
                "lo.descripcion",
                "lo.logo",
                "lo.activo",
            )
            ->where("lo.eliminado", 0)
            ->where("lo.loteria_id", $request->loteria_id)
            ->get();

        return $loteria;
    }


    /* @description EXISTE LA LOTERIA */
    public function existeLoteria($request): bool
    {
        // FORMATEAR
        $nombre = strtoupper(trim($request->nombre));

        return Loteria::where("eliminado", false)
            ->whereRaw("UPPER(nombre) = ?", [$nombre])
            ->notLoteriaId($request->loteria_id)
            ->exists();
    }

    // MÉTODOS PRIVADOS
    private function saveLogo($request, $loteria)
    {
        // VALIDAR LA FOTO Y GUARDAR
        if ($request->hasFile("logo")) {
            $file = $request->file('logo');
            // EXTENCION
            $extension = $file->getClientOriginalExtension();
            // NOMBRE PERSONALZADO
            $logo = $loteria->loteria_id . '.' . $extension;

            // ELIMINAR EL ARCHIVO SI EXISTE
            if ($request->loteria_id && $loteria->logo !== $this->logoDefault) {
                Storage::disk('public_path')->delete($loteria->logo);
            }

            $loteria->logo = "/" . Storage::disk("public_path")->putFileAs($this->logoPath, $file, $logo);
            $loteria->save();
        }
    }
}
