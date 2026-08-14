<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\AtributoService;
use App\Services\GeneralService;
use App\Services\JuegoService;
use App\Utils\Fechas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JuegoController extends Controller
{

    function __construct(private readonly JuegoService $juegoService, private readonly GeneralService $generalService,
                         private readonly AtributoService $atributoService)
    {
    }

    public function index(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->juegoService->index($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description PARA FILTRAR EL INDEX */
    public function filters()
    {
        try {

            $data = ["loterias" => $this->generalService->getLoterias(true)];

            return Helpers::responseJSON(true, "Lista", $data);

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description DATA PARA CREAR REGISTROS */
    public function create()
    {
        try {

            $data = [
                "loterias" => $this->generalService->getLoterias(),
                "mecanismos_juegos" => $this->generalService->getMecanismosJuegos(),
                "calculos_jugadas" => $this->atributoService->getCatAtributos("CALCULOS_JUGADAS"),
                "dias" => Fechas::getDiasArray(),
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

            if ($this->juegoService->existeJuego($request)) {
                return Helpers::responseJSON(false, "Ya existe un Registro con patrón de carácteres similares");
            }
            DB::beginTransaction();

            $juego = $request->juego_id
                ? $this->juegoService->update($request)
                : $this->juegoService->store($request);

            $verbo = $request->juego_id ? "actualizado" : "registrado";

            DB::commit();
            return Helpers::responseJSON(true, "Juego $verbo con éxito", $juego->juego_id);

        } catch (\Throwable $ex) {
            DB::rollBack();
            dd($ex);
            return Helpers::responseJSON(false, "No se pudo registrar", $ex->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->juegoService->edit($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

}
