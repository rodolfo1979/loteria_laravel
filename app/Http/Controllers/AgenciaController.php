<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Agencia;
use App\Services\GeneralService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgenciaController extends Controller
{

    function __construct(private readonly GeneralService $generalService)
    {
    }

    /// FOR EDIT REGISTER
    public function edit(Request $request)
    {
        try {

            $empresa = Agencia::from("empresas AS emp")
                ->select("emp.empresa_id",
                    "emp.nombre_comercial",
                    "emp.razon_social",
                    "emp.numero_ruc",
                    "emp.lema",
                    "emp.email",
                    "emp.telefonos",
                    "emp.website",
                    "emp.direccion",
                    "emp.logo",
                    "emp.mas_50_empleados",
                    "emp.activo")
                ->where("emp.empresa_id", $request->empresa_id)
                ->where("emp.eliminado", 0)
                ->get();

            foreach ($empresa as $tr) {
                $tr->logo = $tr->logo . "?" . time();
            }

            return Helpers::responseJSON(true, "Info de Empresa", $empresa);

        } catch (\Throwable $ex) {
            return Helpers::responseJSON(false, "No se pudo cargar los datos", $ex->getMessage(), 400);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $empresa = Agencia::findOrFail($request->empresa_id);
            $empresa->nombre_comercial = $request->nombre_comercial;
            $empresa->razon_social = $request->razon_social;
            $empresa->numero_ruc = $request->numero_ruc;
            $empresa->lema = $request->lema;
            $empresa->email = $request->email;
            $empresa->telefonos = $request->telefonos;
            $empresa->website = $request->website;
            $empresa->direccion = $request->direccion;
            $empresa->usuario_actualiza_id = Auth::id();
            $empresa->fecha_actualiza = Carbon::now();

            // Validar el logo
            if ($request->hasFile("logo")) {
                $logo = "logo.png";
                Storage::disk("public_path")->putFileAs("/images", $request->file("logo"), $logo);
                $empresa->logo = "/images/" . $logo;
            }

            // GUARDAR
            $empresa->save();

            DB::commit();
            return Helpers::responseJSON(true, "Empresa actualizada con éxito");

        }
        catch (\Throwable $ex) {
            DB::rollBack();
            return Helpers::responseJSON(false, "No se pudo actualizar la Empresa", $ex->getMessage(), 400);
        }
    }

}
