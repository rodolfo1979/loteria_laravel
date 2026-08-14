<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\GeneralService;
use App\Services\UsuarioService;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    function __construct(private readonly GeneralService $generalService,
                         private readonly UsuarioService $usuarioService)
    {
    }

    /**
     * Para crear accesos
     */
    public function accesos(Request $request)
    {
        try {

            // SI NO VIENE EL PARAMETRO PERSONA_ID que tome el AUTH
            $personaId = $request->persona_id ?? auth()->user()->persona_id;

            $datosAccesos = [
                "accesos" => $this->generalService->getPersonaAccesoById($personaId),
                "roles" => $this->generalService->getCatRoles()
            ];

            return Helpers::responseJSON(true, "Lista", $datosAccesos);

        } catch (\Throwable $ex) {
            dd($ex);
        }
    }

    public function save(Request $request)
    {
        try {

            // QUE NO EXISTA
            if ($this->usuarioService->existeUsuario($request)) {
                return Helpers::responseJSON(false, "Ya existe un Nick de Usuario con patrón de carácteres similar.");
            }

            $usuario = $request->usuario_id
                ? $this->usuarioService->update($request)
                : $this->usuarioService->store($request);

            $verbo = $request->usuario_id ? "actualizado" : "registrado";

            return Helpers::responseJSON(true, "Acceso $verbo con éxito.", $usuario);

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo registrar", $ex->getMessage(), 401);
        }
    }

}
