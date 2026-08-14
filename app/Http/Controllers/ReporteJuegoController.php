<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\ReporteJuegoService;
use App\Utils\Fechas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteJuegoController extends Controller
{

    function __construct(private readonly ReporteJuegoService $reporteJuegoService)
    {
    }

    public function proximosSorteos(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->reporteJuegoService->proximosSorteos($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

}
