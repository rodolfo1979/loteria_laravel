<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    function __construct(private readonly AuthService $authService)
    {
    }

    public function login(Request $request)
    {
        try {
            return $this->authService->login($request);
        } catch (\Throwable $ex) {
            dd($ex);
        }
    }

    // LOGOUT
    public function logout(Request $request)
    {
        try {

            $logout = $this->authService->logout($request);

            return Helpers::responseJSON($logout, $logout ? "Sessión cerrada con éxito" : "No se pudo Salir");

        } catch (\Throwable $ex) {
            dd($ex);
        }
    }


    // VALIDAR SESSION
    public function sessionCheck()
    {
        return $this->authService->sessionCheck();
    }
}
