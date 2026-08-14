<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class GeneralService
{

    ////////////// AGENCIA
    public function getInfoAgencia($agencia = 1)
    {
        $agencia = DB::table("agencias AS ag")
            ->select("ag.agencia_id",
                "ag.nombre_comercial",
                "ag.razon_social",
                "ag.rut",
                "ag.lema",
                "ag.email",
                "ag.telefonos",
                "ag.direccion",
                "ag.logo",
                "ag.es_propia"
            )
            ->where("ag.agencia_id", $agencia)
            ->where("ag.eliminado", false)
            ->where("ag.activo", true)
            ->get();

        return json_decode(json_encode($agencia[0]), true);
    }

    ////////////PERSONAS//////////////////
    public function getPersonas($tipoPersonaId = 0, $todos = false): array
    {
        $personas = DB::table("personas AS pe")
            ->leftJoin("usuarios AS us", "us.persona_id", "pe.persona_id")
            ->select(
                "pe.persona_id",
                "pe.numero_identidad",
                "pe.nombres",
                "pe.direccion",
                "pe.email",
                "pe.celular",
                "pe.cat_tipo_persona_id",
                "pe.activo",
                "pe.foto",
                "us.usuario_id",
                "us.usuario",
                "pe.nombres",
                DB::raw("CONCAT(pe.nombres, ' | ', pe.numero_identidad, ' | ', pe.celular) AS nombres_ident_telef"),
            )
            ->where("pe.eliminado", false)
            ->where("pe.activo", true)
            ->where("pe.cat_tipo_persona_id", ">", 1)
            ->orderBy("pe.nombres")
            ->get()->toArray();

        if ($todos) {
            array_unshift($personas, ["persona_id" => null, "nombres" => "Todos", "nombres_ident_telef" => "Todos"]);
        }

        return $personas;
    }

    // GET INFO DE LA PERSONA POR ID
    public function getPersonaById($persona_id): array
    {

        $persona = DB::table("personas AS pe")
            ->leftjoin("usuarios AS us", "us.persona_id", "pe.persona_id")
            ->select(
                "pe.persona_id",
                "pe.nombres",
                "pe.numero_identidad",
                "pe.direccion",
                "pe.email",
                "pe.celular",
                "pe.foto",
                "pe.activo",
                "pe.cat_tipo_persona_id",
                "us.usuario_id",
                "us.usuario",
                DB::raw("CONCAT(pe.numero_identidad, ' ' , pe.nombres) AS  identidad_nombres"),
            )
            ->where("pe.eliminado", false)
            ->where("pe.persona_id", $persona_id)
            ->get()->toArray();

        return json_decode(json_encode($persona[0]), true);

    }

    // GET ACCESO DE PERSONA
    public function getPersonaAccesoById($persona_id): array
    {

        $acceso = DB::table("usuarios AS us")
            ->join("personas AS pe", "pe.persona_id", "us.persona_id")
            ->select(
                "us.persona_id",
                "us.usuario_id",
                "us.usuario",
                "us.cat_rol_id",
                "pe.activo as persona_activo",
            )
            ->where("us.eliminado", false)
            ->where("us.persona_id", $persona_id)
            ->get()->toArray();

        return $acceso;
    }

    // MONEDAS
    public function getCatMonedas(): array
    {
        $monedas = DB::table("cat_monedas AS mo")
            ->select("mo.cat_moneda_id",
                "mo.nombre",
                "mo.simbolo",
                "mo.codigo_ISO",
                "mo.es_principal"
            )
            ->where("mo.eliminado", false)
            ->where("mo.activo", true)
            ->orderBy("mo.cat_moneda_id", "ASC")
            ->get()->toArray();

        return json_decode(json_encode($monedas), true);
    }

    // INFO DE LA LOTERIA
    public function getLoteriaById($loteriaId): array
    {
        $loteria = DB::table("loterias AS lo")
            ->select("lo.loteria_id",
                "lo.nombre",
                "lo.descripcion",
            )
            ->where("lo.eliminado", false)
            ->where("lo.activo", true)
            ->where("lo.loteria_id", $loteriaId)
            ->get()->toArray();

        return json_decode(json_encode($loteria[0]), true);
    }

    /* @description TODAS LAS LOTERIAS */
    public function getLoterias($todos = false): array
    {
        $loteria = DB::table("loterias AS lo")
            ->select("lo.loteria_id",
                "lo.nombre",
                "lo.descripcion",
                "lo.logo",
            )
            ->where("lo.eliminado", false)
            ->where("lo.activo", true)
            ->get()->toArray();

        $array = json_decode(json_encode($loteria), true);

        // AGREGAR EL TODOS
        if ($todos) {
            array_unshift($array, ["loteria_id" => 0, "nombre" => "TODOS"]);
        }

        return $array;
    }

    /* @description TODAS LOS JUEGOS DE LOTERIA */
    public function getJuegos($todos = false): array
    {
        $juegos = DB::table("loterias AS lo")
            ->join("juegos AS ju", "ju.loteria_id", "lo.loteria_id")
            ->select("lo.loteria_id",
                "lo.nombre AS loteria",
                "ju.juego_id",
                "ju.nombre AS juego",
                DB::raw("CONCAT(lo.nombre, ' / ', ju.nombre) AS nombre"),
            )
            ->where("lo.eliminado", false)
            ->where("lo.activo", true)
            ->where("ju.eliminado", false)
            ->where("ju.activo", true)
            ->get()->toArray();

        $array = json_decode(json_encode($juegos), true);

        // AGREGAR EL TODOS
        if ($todos) {
            array_unshift($array, ["juego_id" => 0, "nombre" => "TODOS"]);
        }

        return $array;
    }

    /* @description TODOS JUEGOS DE LOTERIA CON HORAS */
    public function getJuegosSorteos($seleccione = false): array
    {
        $juegosRaw = DB::table("juegos AS ju")
            ->join("loterias AS lo", "lo.loteria_id", "ju.loteria_id")
            ->join("juegos_horas AS jh", "jh.juego_id", "ju.juego_id")
            ->join("juegos_formas_ganar AS jfg", "jfg.juego_id", "ju.juego_id")
            ->join("mecanismos_juegos AS mju", "mju.mecanismo_juego_id", "ju.mecanismo_juego_id")
            ->join("cat_atributos AS cat", "cat.cat_atributo_id", "jfg.calculo_jugada_id")
            ->select(
                "ju.juego_id",
                "ju.nombre AS juego",
                "ju.logo AS juego_logo",
                "ju.mecanismo_juego_id",
                "lo.loteria_id",
                "lo.nombre AS loteria",
                "jh.juego_hora_id",
                "jh.hora",
                "jfg.juego_forma_ganar_id",
                "jfg.modalidad",
                "jfg.premio_veces",
                "jfg.orden_listado",
                "jfg.ejemplo",
                "jfg.calculo_jugada_id",
                "cat.valor1 AS calculo_jugada",
                "mju.nombre AS mecanismo_juego",
            )
            ->where("ju.eliminado", false)
            ->where("ju.activo", true)
            ->where("jh.eliminado", false)
            ->where("jh.activo", true)
            ->where("jfg.eliminado", false)
            ->where("jfg.activo", true)
            ->orderBy("jh.hora", "ASC")
            ->orderBy("jfg.orden_listado", "ASC")
            ->orderBy("lo.nombre", "ASC")
            ->orderBy("ju.nombre", "ASC")
            ->get();

        $juegosSorteos = [];
        foreach ($juegosRaw as $raw) {

            // IF NOT EXIS
            if (!isset($juegosSorteos[$raw->juego_id])) {
                $juegosSorteos[$raw->juego_id] = [
                    "loteria_id" => $raw->loteria_id,
                    "loteria" => $raw->loteria,
                    "juego_id" => $raw->juego_id,
                    "juego" => $raw->juego,
                    "juego_logo" => $raw->juego_logo,
                    "loteria_juego" => "{$raw->loteria} / {$raw->juego}",
                    "mecanismo_juego" => $raw->mecanismo_juego,
                    "mecanismo_juego_id" => $raw->mecanismo_juego_id,
                    "horas" => [],
                    "formas_ganar" => [],
                ];
            }

            // INSTANCE REFERENCE
            $horas = &$juegosSorteos[$raw->juego_id]["horas"];
            $formas_ganar = &$juegosSorteos[$raw->juego_id]["formas_ganar"];

            // IF NOT EXIS
            if (!isset($horas[$raw->juego_hora_id])) {
                $horas[$raw->juego_hora_id] = [
                    "juego_hora_id" => $raw->juego_hora_id,
                    "juego_id" => $raw->juego_id,
                    "hora" => $raw->hora,
                    "hora_fmt" => Carbon::parse($raw->hora)->format('h:i A')
                ];
            }

            // IF NOT EXIS
            if (!isset($formas_ganar[$raw->juego_forma_ganar_id])) {
                $formas_ganar[$raw->juego_forma_ganar_id] = [
                    "juego_forma_ganar_id" => $raw->juego_forma_ganar_id,
                    "juego_id" => $raw->juego_id,
                    "modalidad" => $raw->modalidad,
                    "ejemplo" => $raw->ejemplo,
                    "premio_veces" => $raw->premio_veces,
                    "calculo_jugada_id" => $raw->calculo_jugada_id,
                    "calculo_jugada" => $raw->calculo_jugada,
                    "orden_listado" => $raw->orden_listado,
                ];
            }

            // END EACHS
        }

        // ARRAY VALUES
        foreach ($juegosSorteos as &$sor) {
            $sor["horas"] = array_values($sor["horas"]);
            $sor["formas_ganar"] = array_values($sor["formas_ganar"]);
        }

        // AGREGAR EL SELECIONE
        if ($seleccione) {
            array_unshift($juegosSorteos, ["juego_id" => 0, "loteria_juego" => "Seleccione"]);
        }

        return array_values($juegosSorteos);;
    }

    // GET MECANISMO DE LOS JUEGOS / TIEMPOS (1 NUMERO) 3 MONAZOS (3 NUMEROS)
    public function getMecanismosJuegos(): array
    {
        $mecanismos = DB::table("mecanismos_juegos AS mj")
            ->select("mj.mecanismo_juego_id",
                "mj.nombre",
            )
            ->where("mj.eliminado", false)
            ->where("mj.activo", true)
            ->orderBy("mj.nombre", "ASC")
            ->get()->toArray();

        return json_decode(json_encode($mecanismos), true);
    }

    // CAT ROLES
    public function getCatRoles(): array
    {
        $roles = DB::table("cat_roles AS ro")
            ->select("ro.cat_rol_id",
                "ro.nombre",
            )
            ->where("ro.eliminado", false)
            ->where("ro.activo", true)
            ->orderBy("ro.nombre", "ASC")
            ->get()->toArray();

        return json_decode(json_encode($roles), true);
    }

}
