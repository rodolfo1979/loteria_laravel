<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\RolPaginaAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolPaginaAccionController extends Controller
{
    function __construct(private readonly RolPaginaAccionService $rolPaginaAccionService)
    {
    }

    public function index()
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->rolPaginaAccionService->index());
        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    // PARA CREAR REGISTROS
    public function create(Request $request)
    {
        try {

            $catRolId = $request->cat_rol_id;

            return Helpers::responseJSON(true, "Listas", [
                "paginas_acciones" => $this->rolPaginaAccionService->getPaginasAccionesByRol($catRolId),
                "info" => $catRolId ? $this->rolPaginaAccionService->getRolById($catRolId) : []
            ]);

        } catch (\Throwable $ex) {
            dd($ex);
        }
    }

    // STORE
    public function save(Request $request)
    {
        try {

            DB::beginTransaction();

            if ($this->rolPaginaAccionService->existeCatRol($request)) {
                return Helpers::responseJSON(false, "Ya existe un Rol de Acceso con esta información.");
            }

            $catRol = $request->cat_rol_id
                ? $this->rolPaginaAccionService->updateRol($request)
                : $this->rolPaginaAccionService->storeRol($request);

            $verbo = $request->cat_rol_id ? "actualizado" : "registrado";

            DB::commit();

            return Helpers::responseJSON(true, "Rol $verbo con éxito", $catRol->cat_rol_id);

        } catch (\Throwable $ex) {
            DB::rollBack();
            dd($ex);
        }
    }

}
