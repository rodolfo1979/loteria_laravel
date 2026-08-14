<?php

namespace App\Services;

use App\Models\RolPaginaAccion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Utils\Arrays;

class PermisoService
{

    /* @description MENU DEL USUARIO */
    public function menu($request)
    {
        // Verificar que exista el usuario
        $usuarioId = $request->user()->usuario_id;
        $catRolId = $request->user()->cat_rol_id;

        $menu = DB::table("cat_paginas AS cp")
            ->join("paginas_acciones AS pac", "pac.cat_pagina_id", "cp.cat_pagina_id")
            ->join("cat_acciones AS ca", "ca.cat_accion_id", "pac.cat_accion_id")
            ->select(
                "cp.cat_pagina_id",
                "cp.nombre",
                "cp.slug",
                "cp.icono",
                "cp.orden",
                "cp.cat_padre_id",
            )
            ->groupBy("cp.cat_pagina_id",
                "cp.nombre",
                "cp.slug",
                "cp.icono",
                "cp.orden",
                "cp.cat_padre_id")
            ->when($usuarioId > 1, function ($query) use ($catRolId) {
                return $query->join("roles_paginas_acciones AS rpa", "rpa.pagina_accion_id", "pac.pagina_accion_id")
                    ->where("rpa.cat_rol_id", $catRolId)
                    ->where("rpa.activo", true)
                    ->where("rpa.eliminado", false)
                    // QUE NO MUESTRE LAS PAGINAS SOLO DEV
                    ->where("cp.solo_dev", false);
            })
            ->where("cp.eliminado", false)
            ->where("cp.activo", true)
            ->where("ca.eliminado", false)
            ->where("ca.activo", true)
            ->where("pac.eliminado", false)
            ->where("pac.activo", true)
            ->orderBy("cp.cat_padre_id", "ASC")
            ->orderBy("cp.orden", "ASC")
            ->get();

        // COVERT TO ARRAY
        $array = json_decode(json_encode($menu), true);

        $agrupado = [];

        foreach ($array as $item) {
            // Buscar los padres
            if ($item["cat_padre_id"] == 0) {
                $agrupado[$item["cat_pagina_id"]] = [
                    "cat_pagina_id" => $item["cat_pagina_id"],
                    "title" => $item["nombre"],
                    "link" => $item["slug"],
                    "icon" => $item["icono"],
                    "order" => $item["orden"],
                ];
            }

            // Buscar los hijos
            if ($item["cat_padre_id"] > 1) {
                $agrupado[$item["cat_padre_id"]]["children"][] = [
                    "cat_pagina_id" => $item["cat_pagina_id"],
                    "cat_padre_id" => $item["cat_padre_id"],
                    "title" => $item["nombre"],
                    "link" => $item["slug"],
                    "icon" => $item["icono"],
                    "order" => $item["orden"]
                ];
            }
        }

        // RETURN ORDERED
        return array_values(Arrays::arraySort($agrupado, "order"));
    }

    /* @description CHECK QUE VALIDA PERMISO DE LA PAGINA */
    public function check($request)
    {
        $usuarioId = Auth::id();
        $catRolId = $request->user()->cat_rol_id;
        $slug = $request->slug;

        $permisos = DB::table("cat_paginas AS cp")
            ->join("paginas_acciones AS pac", "pac.cat_pagina_id", "cp.cat_pagina_id")
            ->join("cat_acciones AS ca", "ca.cat_accion_id", "pac.cat_accion_id")
            ->select(
                "pac.cat_accion_id",
                "ca.nombre",
                "ca.orden",
                "ca.cat_accion_id",
            )
            ->groupBy("pac.cat_accion_id",
                "ca.cat_accion_id",
                "ca.nombre",
                "ca.orden",
            )
            ->when($usuarioId > 1, function ($query) use ($catRolId) {
                return $query->join("roles_paginas_acciones AS rpa", "rpa.pagina_accion_id", "pac.pagina_accion_id")
                    ->where("rpa.cat_rol_id", $catRolId)
                    ->where("rpa.activo", true)
                    ->where("rpa.eliminado", false)
                    // QUE NO MUESTRE LAS PAGINAS SOLO DEV
                    ->where("cp.solo_dev", false);

            })
            ->where("cp.eliminado", false)
            ->where("cp.activo", true)
            ->where("ca.eliminado", false)
            ->where("ca.activo", true)
            ->where("pac.eliminado", false)
            ->where("pac.activo", true)
            ->where("cp.slug", $slug)
            ->get();

        $array = json_decode(json_encode($permisos), true);

        $permisosSolo = [];
        if (count($array)) {
            foreach ($array as $data) {
                $permisosSolo[$data["cat_accion_id"]] = $data["cat_accion_id"];
            }
        }

        return $permisosSolo;
    }

    /* @description DEVUELVE LOS HIJOS DE LA PAGINA PADRE */
    public function getChildrens($request)
    {

        $usuarioId = $request->user()->usuario_id;
        $catRolId = $request->user()->cat_rol_id;
        $slug = $request->slug;

        return RolPaginaAccion::from("cat_paginas AS pad")
            ->join("cat_paginas AS cp", "cp.cat_padre_id", "pad.cat_pagina_id")
            ->join("paginas_acciones AS pac", "pac.cat_pagina_id", "cp.cat_pagina_id")
            ->join("cat_acciones AS ca", "ca.cat_accion_id", "pac.cat_accion_id")
            ->select(
                "cp.cat_pagina_id",
                "cp.nombre AS cat_pagina_nombre",
                "cp.slug",
                "cp.icono AS cat_pagina_icono",
                "cp.orden AS cat_pagina_orden",
                "cp.cat_padre_id",
            )
            ->when($usuarioId > 1, function ($query) use ($catRolId) {
                return $query->join("roles_paginas_acciones AS rpa", "rpa.pagina_accion_id", "pac.pagina_accion_id")
                    ->where("rpa.cat_rol_id", $catRolId)
                    ->where("rpa.activo", true)
                    ->where("rpa.eliminado", false)
                    // QUE NO MUESTRE LAS PAGINAS SOLO DEV
                    ->where("cp.solo_dev", false);
            })
            ->where("cp.eliminado", false)
            ->where("cp.activo", true)
            ->where("ca.eliminado", false)
            ->where("ca.activo", true)
            ->where("pac.eliminado", false)
            ->where("pac.activo", true)
            ->where("pac.cat_accion_id", true)
            ->where("pad.slug", $slug)
            ->groupBy(["cp.cat_pagina_id", "cp.nombre", "cp.slug", "cp.icono", "cp.orden", "cp.cat_padre_id"])
            ->orderBy("cp.orden")
            ->get()->toArray();
    }

}
