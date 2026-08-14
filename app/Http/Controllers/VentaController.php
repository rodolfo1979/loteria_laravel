<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Prints\VentaPrint;
use App\Services\GeneralService;
use App\Services\ReporteJuegoService;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    function __construct(private readonly VentaService $ventaService, private readonly ReporteJuegoService $reporteJuegoService, private readonly GeneralService $generalService)
    {
    }

    public function index(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->ventaService->index($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description PARA FILTRAR EL INDEX */
    public function filters()
    {
        try {

            $data = [
                "juegosSorteos" => $this->generalService->getJuegosSorteos(true),
                "personas" => $this->generalService->getPersonas(),
            ];

            return Helpers::responseJSON(true, "Lista", $data);

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description DATA PARA CREAR REGISTROS */
    public function create(Request $request)
    {
        try {

            $data = [
                "juegosSorteos" => $this->generalService->getJuegosSorteos(false),
                "personas" => $this->generalService->getPersonas(),
            ];

            return Helpers::responseJSON(true, "Lista", $data);

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description PROCESO DE GUARDAR Y ACTUALIZAR LOS REGISTROS EN GENERAL */
    public function save(Request $request)
    {
        try {

            if ($this->ventaService->existeVenta($request, 0)) {
                return Helpers::responseJSON(false, "Ya existe un Registro con patrón de carácteres similares");
            }

            DB::beginTransaction();

            $venta = $request->venta_id
                ? $this->ventaService->update($request)
                : $this->ventaService->store($request);

            $verbo = $request->venta_id ? "actualizada" : "registrada";

            DB::commit();
            return Helpers::responseJSON(true, "Venta $verbo con éxito", $venta->venta_id);

        } catch (\Throwable $ex) {
            DB::rollBack();
            dd($ex);
            return Helpers::responseJSON(false, "No se pudo registrar", $ex->getMessage());
        }
    }


    public function edit(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->ventaService->edit($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description PRINT ONE BY ONE */
    public function print(Request $request)
    {
        try {

            $master = $this->ventaService->getVenta($request)[0];
            $details = $this->ventaService->getVentaDetalles($request);
            $agencia = $this->generalService->getInfoAgencia($master["agencia_id"]);

            new VentaPrint($agencia, $master, $details);

        } catch (\Throwable $ex) {
            dd($ex);
        }
    }

}
