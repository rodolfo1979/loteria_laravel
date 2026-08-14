<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    function __construct(private readonly GeneralService   $generalService,
                         private readonly MiSessionService $miSessionService)
    {
    }

    /*  @description INICIAR DEL USUARIO */

    public function login($request)
    {

        $credentials = $request->only('usuario', 'password');
        $usuario = Usuario::query()
            ->where('usuario', $credentials['usuario'] ?? null)
            ->where('activo', true)
            ->where('eliminado', false)
            ->first();

        if ($usuario && Hash::check($credentials['password'] ?? '', $usuario->password)) {
            $token = $usuario->createToken((string) env('APP_NAME', 'LotoX'))->plainTextToken;

            // SAVE SESSION
            $this->miSessionService->save($request, $token);

            // SET PERSONA ID
            $personaId = $usuario->persona_id;

            // PROCESAR ESTADO DE USUARIO
            return $this->estadoUsuario($personaId, $token);

        } else {
            return [
                "status" => "invalid",
                "message" => "Datos incorrectos.",
            ];
        }
    }

    /*  @description LOGOUT */
    public function logout($request)
    {
        // VALIDAR SI HAY SESSION
        if ($request->user()) {
            // Obtener el token del usuario autenticado
            $request->user()->currentAccessToken()?->delete();

            $this->miSessionService->update($request);

            return true;
        } else {
            return false;
        }
    }


    /*  @description VALIDAR SESSION DEL USUARIO */
    public function sessionCheck()
    {
        return response()->json([
            "authenticated" => Auth::guard('sanctum')->check()
        ]);
    }

    /*  @description  DEVOLVER EL ESTADO DEL USUARIO */
    private function estadoUsuario($personaId, $token)
    {

        $info = $this->generalService->getPersonaById($personaId);

        // AHORA EVALUAR LA INFO DEL USER
        if (count($info)) {
            $persona = $info;
            $activo = $persona["activo"];

            // Validar que la cuenta esta activa y no eliminada
            if ($activo) {
                return [
                    "status" => "active",
                    "message" => "Sesion iniciada.",
                    "token" => $token,
                    "data" => $persona,
                ];
            } else {
                return [
                    "status" => "inactive",
                    "message" => "Cuenta suspendida. No puedes ingresar",
                ];
            }
        } else {
            return [
                "status" => "nodata",
                "message" => "No se sabe la información del usaurio",
            ];
        }
    }
}
