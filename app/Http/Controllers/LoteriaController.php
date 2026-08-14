<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\LoteriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoteriaController extends Controller
{

    function __construct(private readonly LoteriaService $loteriaService)
    {
    }

    public function index(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->loteriaService->index($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description PROCESO DE GUARDAR Y ACTUALIZAR LOS REGISTROS EN GENERAL */
    public function save(Request $request)
    {
        try {

            if ($this->loteriaService->existeLoteria($request)) {
                return Helpers::responseJSON(false, "Ya existe un Registro con patrón de carácteres similares");
            }
            DB::beginTransaction();
            $loteria = $request->loteria_id
                ? $this->loteriaService->update($request)
                : $this->loteriaService->store($request);

            $verbo = $request->loteria_id ? "actualizado" : "registrado";

            DB::commit();
            return Helpers::responseJSON(true, "Loteria $verbo con éxito", $loteria->loteria_id);

        } catch (\Throwable $ex) {
            DB::rollBack();
            dd($ex);
            return Helpers::responseJSON(false, "No se pudo registrar", $ex->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->loteriaService->edit($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

}
