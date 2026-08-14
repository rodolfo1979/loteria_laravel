<?php

namespace App\Services;

use App\Models\CatRol;
use App\Models\RolPaginaAccion;
use App\Utils\Arrays;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolPaginaAccionService
{
    function __construct(private readonly GeneralService $generalService)
    {
    }

    public function index()
    {

        $roles = CatRol::from("cat_roles AS cr")
            ->leftjoin("usuarios AS us", "us.cat_rol_id", "cr.cat_rol_id")
            ->leftjoin("personas AS pe", "pe.persona_id", "us.persona_id")
            ->select("cr.cat_rol_id",
                "cr.nombre",
                "cr.descripcion",
                "cr.activo",
                "pe.persona_id",
                "pe.nombres",
                "pe.numero_identidad"
            )
            ->where("cr.eliminado", 0)
            ->orderBy("cr.nombre", "ASC")
            ->get();

        // Convertir to Array
        $array = json_decode(json_encode($roles), true);

        $agrupado = Arrays::groupAndNest($array,
            [
                "roles" => [
                    "cat_rol_id",
                    "nombre",
                    "descripcion",
                    "activo",
                ],
                "personas" => [
                    "persona_id",
                    "nombres",
                    "cat_accion_icono",
                    "numero_identidad",
                ],
            ]);

        return $agrupado["roles"];
    }

    /* @description STORE ROLES */
    public function storeRol($request)
    {

        $rol = new CatRol();
        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->activo = 1;
        $rol->usuario_crea_id = Auth::id();
        $rol->save();

        $permisosRAW = $request->paginas_acciones;
        $permisosAgrupados = $this->procesarPermisosAgrupados($permisosRAW);

        // GO TO STORE
        foreach ($permisosAgrupados as $perag) {
            $this->storePermisos($perag, $rol->cat_rol_id);
        }

        return $rol;
    }

    /* @description UPDATE ROLES */
    public function updateRol($request)
    {

        // ANALIZE STORE OR UPDATE CAT ROL
        $rol = CatRol::findOrFail($request->cat_rol_id);

        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->activo = $request->activo;
        $rol->usuario_actualiza_id = Auth::id();
        $rol->fecha_actualiza = Carbon::now();
        $rol->save();

        $permisosRAW = $request->paginas_acciones;
        $permisosAgrupados = $this->procesarPermisosAgrupados($permisosRAW);

        // GO TO STORE or UPDATE PERMISOS
        foreach ($permisosAgrupados as $perag) {
            if ($perag["rol_pagina_accion_id"]) {
                $this->updatePermisos($perag);
            } else if (!$perag["rol_pagina_accion_id"] && $perag["activo"]) {
                $this->storePermisos($perag, $rol->cat_rol_id);
            }
        }

        return $rol;
    }

    // GET FOR ID
    public function getRolById($catRolId): array
    {

        $rol = CatRol::from("cat_roles AS cr")
            ->select("cr.cat_rol_id",
                "cr.nombre",
                "cr.descripcion",
                "cr.activo")
            ->where("cr.cat_rol_id", $catRolId)
            ->where("cr.eliminado", 0)
            ->get();

        return json_decode(json_encode($rol), true)[0];
    }

    // GET PAGINAS ACCIONES BY ROL
    public function getPaginasAccionesByRol($catRolId): array
    {
        $paginasAcciones = DB::table("paginas_acciones AS pac")
            ->join("cat_paginas AS cp", "cp.cat_pagina_id", "pac.cat_pagina_id")
            ->join("cat_acciones AS ca", "ca.cat_accion_id", "pac.cat_accion_id")
            ->leftJoin("roles_paginas_acciones AS rpa", function ($join) use ($catRolId) {
                $join->on("rpa.pagina_accion_id", "pac.pagina_accion_id")
                    ->where("rpa.cat_rol_id", $catRolId)
                    ->where("rpa.activo", 1)
                    ->where("rpa.eliminado", 0);
            })
            ->select(
                "pac.pagina_accion_id",
                "cp.cat_pagina_id",
                "cp.nombre AS cat_pagina_nombre",
                "cp.slug",
                "cp.icono AS cat_pagina_icono",
                "cp.orden AS cat_pagina_orden",
                DB::raw("COALESCE(cp.cat_padre_id, 0) AS cat_padre_id"),
                "ca.cat_accion_id",
                "ca.nombre AS cat_accion_nombre",
                "ca.icono AS cat_accion_icono",
                DB::raw("COALESCE(rpa.rol_pagina_accion_id, 0) AS rol_pagina_accion_id"),
                DB::raw("COALESCE(rpa.activo, false) AS activo"),
            )
            ->where("cp.eliminado", 0)
            // QUE NO MUESTRE LAS PAGINAS SOLO DEV
            ->where("cp.solo_dev", 0)
            ->where("cp.activo", 1)
            ->where("ca.eliminado", 0)
            ->where("ca.activo", 1)
            ->where("pac.eliminado", 0)
            ->where("pac.activo", 1)
            ->orderBy("cp.cat_padre_id")
            ->orderBy("cp.orden")
            ->orderBy("ca.orden")
            ->get()->toArray();

        $json = json_encode($paginasAcciones);

        $array = json_decode($json, true);

        $agrupado = Arrays::groupAndNest($array,
            [
                "paginas" => [
                    "cat_pagina_id",
                    "cat_pagina_nombre",
                    "slug",
                    "cat_pagina_icono",
                    "cat_pagina_orden",
                    "cat_padre_id",
                ],
                "acciones" => [
                    "cat_accion_id",
                    "cat_accion_nombre",
                    "cat_accion_icono",
                    "pagina_accion_id",
                    "rol_pagina_accion_id",
                    "cat_rol_id",
                    "activo",
                ],
            ]);

        $ordenado = [];
        foreach ($agrupado["paginas"] as $item) {
            // Buscar los padres
            if ($item["cat_padre_id"] == 0) {

                $ordenado[$item["cat_pagina_id"]] = [
                    "cat_pagina_id" => $item["cat_pagina_id"],
                    "cat_pagina_nombre" => $item["cat_pagina_nombre"],
                    "slug" => $item["slug"],
                    "cat_pagina_icono" => $item["cat_pagina_icono"],
                    "cat_pagina_orden" => $item["cat_pagina_orden"],
                    "acciones" => $item["acciones"],
                ];
            }

            // Buscar los hijos
            if ($item["cat_padre_id"]) {
                $ordenado[$item["cat_padre_id"]]["hijos"][] = [
                    "cat_pagina_id" => $item["cat_pagina_id"],
                    "cat_pagina_nombre" => $item["cat_pagina_nombre"],
                    "slug" => $item["slug"],
                    "cat_pagina_icono" => $item["cat_pagina_icono"],
                    "cat_pagina_orden" => $item["cat_pagina_orden"],
                    "acciones" => $item["acciones"],
                ];
            }
        }
        return array_values($ordenado);
    }

    /**
     * @description EXISTE
     */
    public function existeCatRol($request): bool
    {
        $nombre = strtoupper(trim($request->nombre));

        return CatRol::where("eliminado", false)
            ->whereRaw("UPPER(nombre) = ?", [$nombre])
            ->whereNot("cat_rol_id", $request->cat_rol_id)
            ->exists();
    }


    /* @description STORE ROLES ACCIONES */
    private function storePermisos($request, $catRolId)
    {

        $rolPaginaAccion = new RolPaginaAccion();
        $rolPaginaAccion->cat_rol_id = $catRolId;
        $rolPaginaAccion->pagina_accion_id = $request["pagina_accion_id"];
        $rolPaginaAccion->usuario_crea_id = Auth::id();
        $rolPaginaAccion->fecha_crea = Carbon::now();
        $rolPaginaAccion->save();

        return $rolPaginaAccion;
    }

    /* @description UPDATE ROLES ACCIONES */
    private function updatePermisos($request)
    {

        $rolPaginaAccion = RolPaginaAccion::findOrFail($request["rol_pagina_accion_id"]);
        $rolPaginaAccion->activo = (boolean)$request["activo"];
        $rolPaginaAccion->usuario_actualiza_id = Auth::id();
        $rolPaginaAccion->fecha_actualiza = Carbon::now();
        $rolPaginaAccion->save();

        return $rolPaginaAccion;
    }

    /* @description PROCESAR LOS PERMISOS AGRUPADOS PARA STORE OR UPDATE */
    private function procesarPermisosAgrupados($permisosRAW)
    {
        $permisosAgrupados = [];
        // SACAR EN UN SOLO ARRAY LOS DATOS PARA PROCESAR
        foreach ($permisosRAW as &$per) {
            if (isset($per["acciones"])) {
                $permisosAgrupados = array_merge($permisosAgrupados, $per["acciones"]);
            }

            if (isset($per["hijos"])) {
                foreach ($per["hijos"] as &$hij) {
                    if (isset($hij["acciones"])) {
                        $permisosAgrupados = array_merge($permisosAgrupados, $hij["acciones"]);
                    }
                }
            }
        }

        return $permisosAgrupados;
    }
}
