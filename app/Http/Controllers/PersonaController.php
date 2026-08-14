<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\GeneralService;
use App\Services\PersonaService;
use App\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{

    function __construct(private readonly PersonaService $personaService,
                         private readonly UsuarioService $usuarioService,
                         private readonly GeneralService $generalService)
    {
    }

    public function index(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista index", $this->personaService->index($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }


    /* @description INFO DE LAS PERSONAS, SI NO VIENE PARAMETRO QUE TOME LA SESSION */
    public function info(Request $request)
    {
        try {

            $personaId = $request->persona_id ?? auth()->user()->persona_id;

            return Helpers::responseJSON(true, "Lista", $this->generalService->getPersonaById($personaId));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description iniciales para registrar */
    public function create(Request $request)
    {
        try {

            // DO SOME
            return Helpers::responseJSON(true, "Lista", []);

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

    /* @description PROCESO DE GUARDAR Y ACTUALIZAR LOS REGISTROS EN GENERAL */
    public function save(Request $request)
    {
        try {

            if ($this->personaService->existePersona($request)) {
                return Helpers::responseJSON(false, "Ya existe un Registro con patrón de carácteres similares");
            }
            DB::beginTransaction();
            $persona = $request->persona_id
                ? $this->personaService->update($request)
                : $this->personaService->store($request);

            $verbo = $request->persona_id ? "actualizado" : "registrado";

            DB::commit();
            return Helpers::responseJSON(true, "Persona $verbo con éxito", $persona->persona_id);

        } catch (\Throwable $ex) {
            DB::rollBack();
            dd($ex);
            return Helpers::responseJSON(false, "No se pudo registrar", $ex->getMessage());
        }
    }

    /* @description PROCESO DE GUARDAR POR PROPIA CUENTA AL CLIENTE */
    public function clienteStore(Request $request)
    {
        try {

            DB::beginTransaction();

            if ($this->personaService->existePersona($request)) {
                return Helpers::responseJSON(false, "Ya existe un Registro con patrón de carácteres similares");
            } else {
                // GUARDAR PERSONA
                $persona = $this->personaService->store($request);

                // GUARDAR USUARIO
                $request->persona_id = $persona->persona_id;
                $this->usuarioService->store($request);

                DB::commit();

                return Helpers::responseJSON(true, "Se ha registrado con éxito. Será dirigido para ingresar al Sistema.", $persona->persona_id);
            }

        } catch (\Throwable $ex) {
            DB::rollBack();
            dd($ex);
            return Helpers::responseJSON(false, "No se pudo registrar", $ex->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Lista", $this->personaService->edit($request));

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo listar", $ex->getMessage(), 400);
        }
    }

}
