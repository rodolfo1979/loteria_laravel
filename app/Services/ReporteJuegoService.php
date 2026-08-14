<?php

namespace App\Services;

use App\Models\Juego;
use App\Models\Venta;
use App\Models\SorteoDia;
use App\Models\VentaDetalle;
use App\Utils\Fechas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReporteJuegoService
{

    public function proximosSorteos($request)
    {
        $juegoId = $request->juego_id ?? null;

        $horas = [];
        $sorteos = Juego::from("juegos AS ju")
            ->join("juegos_horas AS jh", "jh.juego_id", "ju.juego_id")
            ->join("loterias AS lo", "lo.loteria_id", "ju.loteria_id")
            ->select(
                "ju.juego_id",
                "ju.nombre AS juego",
                "ju.logo AS juego_logo",
                "lo.loteria_id",
                "lo.nombre AS loteria",
                "jh.juego_hora_id",
                "jh.hora",
            )
            ->where("ju.eliminado", false)
            ->where("ju.activo", true)
            ->juegoId($juegoId)
            ->where("jh.eliminado", false)
            ->where("jh.activo", true)
            ->orderBy("jh.hora", "ASC")
            ->orderBy("lo.nombre", "ASC")
            ->orderBy("ju.nombre", "ASC")
            ->get();

        // PROCESAR
        foreach ($sorteos AS $so) {
            $so->horaFmt = Carbon::parse($so->hora)->format('h:i A');
            $so->sorteo_hora = "{$so->loteria} / {$so->juego} / {$so->horaFmt}";
            // FILL HORAS
            $horas[] = $so->hora;
        }

        return [
            "sorteos" => $sorteos,
            "horas" => array_values(array_unique($horas))
        ];
    }


}
