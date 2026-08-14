<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Services\PermisoService;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    function __construct(private readonly PermisoService $permisoService)
    {
    }

    public function menu(Request $request)
    {
        try {
            return Helpers::responseJSON(true, "Lista", $this->permisoService->menu($request));
        } catch (\Throwable $ex) {
            echo $ex->getMessage();
        }
    }

    public function check(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Permiso validado con éxito", $this->permisoService->check($request));

        } catch (\Throwable $ex) {
            echo $ex->getMessage();
        }
    }

    public function getChildrens(Request $request)
    {
        try {

            return Helpers::responseJSON(true, "Permiso validado con éxito", $this->permisoService->getChildrens($request));

        } catch (\Throwable $ex) {
            dd($ex);
        }
    }

}
