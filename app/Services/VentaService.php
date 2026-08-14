<?php

namespace App\Services;

use App\Http\Prints\VentaPrint;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Utils\UtilsGenerales;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VentaService
{
    public function __construct(private readonly MiSessionService $miSessionService)
    {
    }

    public function index($request)
    {
        $rowsPage = $request->filters["rowsPage"] ?? 15;
        $search = $request->filters["search"] ?? null;
        $juegoId = $request->filters["juego_id"] ?? null;

        $ventas = Venta::from("ventas AS ve")
            ->join("juegos AS ju", "ju.juego_id", "ve.juego_id")
            ->join("loterias AS lo", "lo.loteria_id", "ju.loteria_id")
            ->join("personas AS cl", "cl.persona_id", "ve.cliente_id")
            ->join("agencias AS ag", "ag.agencia_id", "ve.agencia_id")
            ->leftJoin("personas AS vnd", "vnd.persona_id", "ve.vendedor_id")
            ->select(
                "ve.venta_id",
                "ve.numero AS venta_numero",
                "ve.fecha_sorteo",
                "ve.fecha_crea",
                "ve.activo",
                "ve.total",
                "ju.nombre AS juego",
                "lo.loteria_id",
                "lo.nombre AS loteria",
                "cl.nombres AS cliente",
                "vnd.nombres AS vendedor",
                "ag.nombre_comercial AS agencia",
            )
            ->where("ve.eliminado", false)
            ->search($search)
            ->juegoId($juegoId)
            ->orderBy("ve.numero", "DESC")
            ->orderBy("ve.fecha_crea", "DESC")
            ->paginate($rowsPage);

        return [
            "pagination" => [
                "total" => $ventas->total(),
                "current_page" => $ventas->currentPage(),
                "per_page" => $ventas->perPage(),
                "last_page" => $ventas->lastPage(),
                "from" => $ventas->firstItem(),
                "to" => $ventas->lastItem(),
            ],
            "result" => $ventas
        ];

        // PROCESAR CON EL RESTO DE DATOS PARA SACAR LAS FECHAS Y HORAS
    }

    /* @description STORE */
    public function store($request)
    {
        // GET LAST NUMBER TICKET

        $venta = new Venta();
        $venta->numero = $this->getUltimoNumeroVenta($request);
        $venta->serie = $request->serie ?? null;
        $venta->fecha_sorteo = $request->fecha_sorteo;
        $venta->juego_id = $request->juego_id;
        $venta->agencia_id = $request->agencia_id;
        $venta->cliente_id = $request->cliente_id;
        $venta->vendedor_id = $request->vendedor_id ?? Auth::id();
        $venta->observacion = $request->observacion;
        $venta->comision_porcentaje = $request->comision_porcentaje ?? 1;
        $venta->total = $request->total;
        $venta->ip_address = $request->ip();
        $venta->user_agent = $this->miSessionService->getUserAgent($request);
        $venta->latitude = $request->latitude ?? null;
        $venta->longitude = $request->longitude ?? null;
        $venta->usuario_crea_id = Auth::id();
        $venta->save();

        // GUARDAR LOS DETALLES
        $this->storeVentaDetalles($venta, $request->detalles);

        return $venta;
    }

    /* @description STORE */
    public function update($request)
    {

        $venta = Venta::findOrFail($request->venta_id);
        $venta->numero = $request->numero;
        $venta->serie = $request->serie ?? null;
        $venta->fecha_sorteo = $request->fecha_sorteo;
        $venta->juego_id = $request->juego_id;
        $venta->agencia_id = $request->agencia_id;
        $venta->cliente_id = $request->cliente_id;
        $venta->vendedor_id = $request->vendedor_id ?? Auth::id();;
        $venta->observacion = $request->observacion;
        $venta->comision_porcentaje = $request->comision_porcentaje;
        $venta->total = $request->total;
        $venta->ip_address = $request->ip();
        $venta->user_agent = $this->miSessionService->getUserAgent($request);
        $venta->latitude = $request->latitude ?? null;
        $venta->longitude = $request->longitude ?? null;
        $venta->activo = $request->activo ?? true;
        $venta->usuario_actualiza_id = Auth::id();
        $venta->fecha_actualiza = Carbon::now();
        $venta->save();

        // GUARDAR LOS DETALLES
        $this->storeVentaDetalles($venta, $request->detalles);

        return $venta;
    }

    public function edit($request)
    {

        return [
            "master" => $this->getVenta($request),
            "details" => $this->getVentaDetalles($request),
        ];
    }

    /* @description EXISTE LA VENTA */
    public function existeVenta($request, $numero): bool
    {
        // FORMATEAR
        $numero = $request->juego_id;

        return Venta::where("eliminado", false)
            ->where("numero", $numero)
            ->where("agencia_id", $request->agencia_id)
            ->notVentaId($request->venta_id)
            ->exists();
    }

    /* @description GET VENTA */
    public function getVenta($venta)
    {
        $master = Venta::from("ventas AS ve")
            ->join("juegos AS ju", "ju.juego_id", "ve.juego_id")
            ->join("loterias AS lo", "lo.loteria_id", "ju.loteria_id")
            ->join("personas AS cl", "cl.persona_id", "ve.cliente_id")
            ->join("agencias AS ag", "ag.agencia_id", "ve.agencia_id")
            ->leftJoin("personas AS vnd", "vnd.persona_id", "ve.vendedor_id")
            ->select(
                "ve.venta_id",
                "ve.numero AS venta_numero",
                "ve.fecha_sorteo",
                "ve.fecha_crea",
                "ve.total",
                "ve.activo",
                "ju.nombre AS juego",
                "lo.loteria_id",
                "lo.nombre AS loteria",
                "cl.nombres AS cliente",
                "vnd.nombres AS vendedor",
                "ag.agencia_id",
                "ag.nombre_comercial AS agencia",
            )
            ->where("ve.eliminado", false)
            ->where("ve.venta_id", $venta->venta_id)
            ->get();

        return json_decode(json_encode($master), true);
    }

    /* @description GET VENTA DETALLES */
    public function getVentaDetalles($venta)
    {
        $detalles = VentaDetalle::from("ventas_detalles AS ved")
            ->join("juegos_formas_ganar AS jfg", "jfg.juego_forma_ganar_id", "ved.juego_forma_ganar_id")
            ->join("cat_atributos AS cat", "cat.cat_atributo_id", "jfg.calculo_jugada_id")
            ->select(
                "ved.venta_detalle_id",
                "ved.numero AS numero",
                "ved.hora",
                "ved.monto",
                "jfg.modalidad",
                "jfg.premio_veces",
                "cat.valor1 AS calculo_jugada",
            )
            ->where("ved.activo", true)
            ->where("ved.eliminado", false)
            ->where("ved.venta_id", $venta->venta_id)
            ->orderBy("ved.venta_detalle_id", "ASC")
            ->get();

        $array = json_decode(json_encode($detalles), true);

        // PROCESS
        foreach ($array as &$arr) {
            $arr["horaFmt"] = Carbon::parse($arr["hora"])->format('h:i A');
        }

        return $array;
    }

    //////////// MÉTODOS PRIVADOS
    private function storeVentaDetalles($venta, $detalles)
    {
        foreach ($detalles as $det) {

            // ANALIZAR SI VIENEN ELIMINADOS
            if ($det["eliminado"] && $det["venta_detalle_id"]) {
                $ventaDet = VentaDetalle::findOrFail($det["venta_detalle_id"]);
                $ventaDet->eliminado = true;
                $ventaDet->activo = false;
                $ventaDet->usuario_actualiza_id = Auth::id();
                $ventaDet->fecha_actualiza = Carbon::now();
            }

            // ANALIZAR SI VIENEN PARA ACTUALIZAR
            if (!$det["eliminado"] && $det["venta_detalle_id"]) {
                $ventaDet = VentaDetalle::findOrFail($det["venta_detalle_id"]);
                $ventaDet->numero = $det["numero"];
                $ventaDet->hora = $det["hora"];
                $ventaDet->juego_forma_ganar_id = $det["juego_forma_ganar_id"];
                $ventaDet->monto = $det["monto"];
                $ventaDet->usuario_actualiza_id = Auth::id();
                $ventaDet->fecha_actualiza = Carbon::now();
            }

            // ANALIZAR SI ES NUEVO
            if (!$det["eliminado"] && !$det["venta_detalle_id"]) {
                $ventaDet = new VentaDetalle();
                $ventaDet->venta_id = $venta["venta_id"];
                $ventaDet->numero = $det["numero"];
                $ventaDet->hora = $det["hora"];
                $ventaDet->juego_forma_ganar_id = $det["juego_forma_ganar_id"];
                $ventaDet->monto = $det["monto"];
                $ventaDet->usuario_crea_id = Auth::id();
            }

            // SAVE ALL
            $ventaDet->save();
        }
    }

    private function getUltimoNumeroVenta($request): string
    {
        $venta = Venta::select("numero")
            ->where("agencia_id", $request->agencia_id)
            ->where("eliminado", 0)
            ->orderBy("venta_id", "DESC")
            ->take(1)
            ->get();

        $numeroAnterior = 0;
        if (count($venta)) {
            $numeroAnterior = $venta->pluck("numero")[0];
        }

        return UtilsGenerales::documentoNumero($numeroAnterior, "T");
    }

}
