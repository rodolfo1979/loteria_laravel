<?php

namespace App\Services;

use App\Models\Juego;
use App\Models\JuegoDia;
use App\Models\JuegoFormaGanar;
use App\Models\JuegoHora;
use App\Utils\Fechas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JuegoService
{
    private string $logoPath = "images/juegos/logos";
    private string $logoDefault = "images/juegos/logos/default.png";

    public function index($request)
    {
        $rowsPage = $request->filters["rowsPage"] ?? 15;
        $search = $request->filters["search"] ?? null;
        $loteriaId = $request->filters["loteria_id"] ?? null;

        $juegos = Juego::from("juegos AS ju")
            ->join("loterias AS lo", "lo.loteria_id", "ju.loteria_id")
            ->select(
                "ju.juego_id",
                "ju.loteria_id",
                "ju.nombre",
                "ju.descripcion",
                "ju.logo",
                "ju.activo",
                "lo.nombre AS loteria"
            )
            ->where("ju.eliminado", 0)
            ->search($search)
            ->loteriaId($loteriaId)
            ->orderBy("lo.nombre", "ASC")
            ->orderBy("ju.nombre", "ASC")
            ->paginate($rowsPage);

        return [
            "pagination" => [
                "total" => $juegos->total(),
                "current_page" => $juegos->currentPage(),
                "per_page" => $juegos->perPage(),
                "last_page" => $juegos->lastPage(),
                "from" => $juegos->firstItem(),
                "to" => $juegos->lastItem(),
            ],
            "result" => $juegos
        ];
    }

    /* @description STORE */
    public function store($request)
    {
        $juego = new Juego();
        $juego->loteria_id = $request->loteria_id;
        $juego->nombre = $request->nombre;
        $juego->descripcion = $request->descripcion ?? null;
        $juego->mecanismo_juego_id = $request->mecanismo_juego_id;
        $juego->logo = $this->logoDefault;
        $juego->usuario_crea_id = Auth::id();
        $juego->save();

        // ACTUALIZAR LA FOTO
        $this->saveLogo($request, $juego);

        // GUARDAR LOS DIAS
        $this->storeUpdateJuegoDias($juego, $request->dias);
        // GUARDAR LAS HORAS
        $this->storeUpdateJuegoHoras($juego, $request->horas);
        // GUARDAR LAS FORMAS DE PAGAR
        $this->storeUpdateJuegoFormasGanar($juego, $request->formas_ganar);

        return $juego;
    }

    /* @description STORE */
    public function update($request)
    {
        $juego = Juego::findOrFail($request->juego_id);
        $juego->loteria_id = $request->loteria_id;
        $juego->nombre = $request->nombre;
        $juego->descripcion = $request->descripcion ?? null;
        $juego->mecanismo_juego_id = $request->mecanismo_juego_id;
        $juego->activo = $request->activo ?? true;
        $juego->usuario_actualiza_id = Auth::id();
        $juego->fecha_actualiza = Carbon::now();
        $juego->save();

        // ACTUALIZAR LA FOTO
        $this->saveLogo($request, $juego);

        // GUARDAR LOS DIAS
        $this->storeUpdateJuegoDias($juego, $request->dias);
        // GUARDAR LAS HORAS
        $this->storeUpdateJuegoHoras($juego, $request->horas);
        // GUARDAR LAS FORMAS DE PAGAR
        $this->storeUpdateJuegoFormasGanar($juego, $request->formas_ganar);

        return $juego;
    }

    /* RETURN JUEGO CON DIAS, HORAS, FORMAS DE GANAR*/
    public function edit($request)
    {
        $juegoCollection = Juego::from("juegos AS ju")
            ->select("ju.juego_id",
                "ju.nombre",
                "ju.loteria_id",
                "ju.descripcion",
                "ju.logo",
                "ju.mecanismo_juego_id",
                "ju.activo",
            )
            ->where("ju.eliminado", 0)
            ->where("ju.juego_id", $request->juego_id)
            ->get();

        // ASSIGN
        $juego = $juegoCollection[0];

        // GET DIAS
        $juego->dias = json_decode(json_encode($this->getJuegoDias($juego)->pluck("dia")));
        // GET HORAS
        $juego->horas = $this->getJuegoHoras($juego);
        // GET FORMAS GANAR
        $juego->formas_ganar = $this->getJuegoFormasGanar($juego);
        // RETURN FULL DATA
        return $juego;
    }

    /* @description EXISTE el JUEGO */
    public function existeJuego($request): bool
    {
        // FORMATEAR
        $nombre = strtoupper(trim($request->nombre));

        return Juego::where("eliminado", false)
            ->whereRaw("UPPER(nombre) = ?", [$nombre])
            ->notJuegoId($request->juego_id)
            ->exists();
    }

    // MÉTODOS PRIVADOS
    private function saveLogo($request, $juego)
    {
        // VALIDAR LA FOTO Y GUARDAR
        if ($request->hasFile("logo")) {
            $file = $request->file('logo');
            // EXTENCION
            $extension = $file->getClientOriginalExtension();
            // NOMBRE PERSONALZADO
            $logo = $juego->juego_id . '.' . $extension;

            // ELIMINAR EL ARCHIVO SI EXISTE
            if ($request->juego_id && $juego->logo !== $this->logoDefault) {
                Storage::disk('public_path')->delete($juego->logo);
            }

            $juego->logo = "/" . Storage::disk("public_path")->putFileAs($this->logoPath, $file, $logo);
            $juego->save();
        }
    }

    /* @description STORE OR UPDATE DIAS DEL JUEGO */
    private function storeUpdateJuegoDias($juego, $diasString)
    {
        if (isset($diasString)) {
            // FORMAT STRING TO ARRAY
            $diasArr = explode(',', $diasString);

            // PLUCK Y CONVERT TO ARRAY, LOS DIAS DEL JUEGO QUE YA EXISTEN
            $soloDias = json_decode(json_encode($this->getJuegoDias($juego)->pluck("dia")));

            // DESACTIVARLOS A TODOS PRELIMINARMENTE
            JuegoDia::where('juego_id', $juego->juego_id)
                ->update([
                    'activo' => false,
                    'eliminado' => true
                ]);

            foreach ($diasArr as $di) {

                // VALIDAR QUE EXISTE
                if (in_array($di, $soloDias)) {
                    // ACTIVAMOS
                    JuegoDia::where('juego_id', $juego->juego_id)
                        ->where("dia", $di)
                        ->update([
                            'activo' => true,
                            'eliminado' => false
                        ]);
                } else {
                    // NUEVO REGISTRO
                    $juegoDia = new JuegoDia();
                    $juegoDia->juego_id = $juego->juego_id;
                    $juegoDia->dia = $di;
                    $juegoDia->usuario_crea_id = Auth::id();
                    $juegoDia->save();
                }
            }
        }
    }

    /* @description STORE OR UPDATE HORAS DEL JUEGO */
    private function storeUpdateJuegoHoras($juego, $horasArr)
    {
      if(isset($horasArr)) {
          // FOREACH
          foreach ($horasArr as $hora) {
              // DECODE

              $ho = json_decode($hora);
              // ANALIZAR SI VIENEN ELIMINADOS
              if ($ho->eliminado && $ho->juego_hora_id) {
                  $juegoHora = JuegoHora::findOrFail($ho->juego_hora_id);
                  $juegoHora->eliminado = true;
                  $juegoHora->activo = false;
                  $juegoHora->usuario_actualiza_id = Auth::id();
                  $juegoHora->fecha_actualiza = Carbon::now();
              }
              // SI ES EDITAR
              if (!$ho->eliminado && $ho->juego_hora_id) {
                  $juegoHora = JuegoHora::findOrFail($ho->juego_hora_id);
                  $juegoHora->alias = $ho->alias ?? Fechas::getPartesDelDia($ho->hora);
                  $juegoHora->hora = Carbon::parse($ho->hora)->format("H:i");
                  $juegoHora->usuario_actualiza_id = Auth::id();
                  $juegoHora->fecha_actualiza = Carbon::now();
              }
              // SI ES NUEVO
              if (!$ho->eliminado && !$ho->juego_hora_id) {
                  $juegoHora = new JuegoHora();
                  $juegoHora->juego_id = $juego->juego_id;
                  $juegoHora->alias = $ho->alias ?? Fechas::getPartesDelDia($ho->hora);
                  $juegoHora->hora = Carbon::parse($ho->hora)->format("H:i");
                  $juegoHora->usuario_crea_id = Auth::id();
              }
              // SAVE ALL
              $juegoHora->save();
          }
      }
    }

    /* @description STORE OR UPDATE HORAS DEL JUEGO */
    private function storeUpdateJuegoFormasGanar($juego, $formasGanarArr)
    {
       if(isset($formasGanarArr)) {
           foreach ($formasGanarArr as $formasGanar) {
               // DECODE
               $jfg = json_decode($formasGanar);

               // ANALIZAR SI VIENEN ELIMINADOS
               if ($jfg->eliminado && $jfg->juego_forma_ganar_id) {
                   $juegoFormaGanar = JuegoFormaGanar::findOrFail($jfg->juego_forma_ganar_id);
                   $juegoFormaGanar->eliminado = true;
                   $juegoFormaGanar->activo = false;
                   $juegoFormaGanar->usuario_actualiza_id = Auth::id();
                   $juegoFormaGanar->fecha_actualiza = Carbon::now();
               }
               // SI ES EDITAR
               if (!$jfg->eliminado && $jfg->juego_forma_ganar_id) {
                   $juegoFormaGanar = JuegoFormaGanar::findOrFail($jfg->juego_forma_ganar_id);
                   $juegoFormaGanar->modalidad = $jfg->modalidad;
                   $juegoFormaGanar->ejemplo = $jfg->ejemplo ?? null;
                   $juegoFormaGanar->premio_veces = $jfg->premio_veces;
                   $juegoFormaGanar->calculo_jugada_id = $jfg->calculo_jugada_id;
                   $juegoFormaGanar->orden_listado = $jfg->orden_listado;
                   $juegoFormaGanar->usuario_actualiza_id = Auth::id();
                   $juegoFormaGanar->fecha_actualiza = Carbon::now();
               }
               // SI ES NUEVO
               if (!$jfg->eliminado && !$jfg->juego_forma_ganar_id) {
                   $juegoFormaGanar = new JuegoFormaGanar();
                   $juegoFormaGanar->juego_id = $juego->juego_id;
                   $juegoFormaGanar->modalidad = $jfg->modalidad;
                   $juegoFormaGanar->ejemplo = $jfg->ejemplo ?? null;
                   $juegoFormaGanar->premio_veces = $jfg->premio_veces;
                   $juegoFormaGanar->calculo_jugada_id = $jfg->calculo_jugada_id;
                   $juegoFormaGanar->orden_listado = $jfg->orden_listado;
                   $juegoFormaGanar->usuario_crea_id = Auth::id();
               }
               // SAVE ALL
               $juegoFormaGanar->save();
           }
       }
    }


    /* @description GET DIAS BY JUEGO */
    private function getJuegoDias($juego)
    {
        $juegoDias = JuegoDia::where("juego_id", $juego->juego_id)
            ->where("eliminado", false)
            ->where("activo", true)
            ->orderBy("dia", "ASC")
            ->get();

        // PROCESS FOR FORMAT DAY
        foreach ($juegoDias as $jd) {
            $jd->dia_nombre = Fechas::diaNombreCorto((int)$jd->dia - 1);
        }

        return $juegoDias;
    }

    /* @description GET HORAS BY JUEGO */
    private function getJuegoHoras($juego)
    {
        $juegoHoras = JuegoHora::where("juego_id", $juego->juego_id)
            ->where("activo", true)
            ->where("eliminado", false)
            ->orderBy("hora", "ASC")
            ->get();

        // PROCESS FOR FORMAT TIME
        foreach ($juegoHoras as $ho) {
            $ho->hora_formato = Carbon::parse($ho->hora)->format('h:i A');
        }

        return $juegoHoras;
    }

    /* @description GET FORMAS GANAR BY JUEGO */
    private function getJuegoFormasGanar($juego)
    {
        return JuegoFormaGanar::where("juego_id", $juego->juego_id)
            ->where("activo", true)
            ->where("eliminado", false)
            ->orderBy("orden_listado", "ASC")
            ->get();
    }

}
