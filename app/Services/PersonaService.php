<?php

namespace App\Services;

use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersonaService
{
    private string $fotoDefault = "images/personas/fotos/default.jpg";
    private string $fotoPath = "images/personas/logos";

    public function index($request)
    {
        $rowsPage = $request->rowsPage ?? 15;
        $busqueda = $request->filtros["search"] ?? null;
        $catTipoPersonaId = $request->filtros["cat_tipo_persona_id"];

        $personas = Persona::from("personas AS pe")
            ->select(
                "pe.persona_id",
                "pe.nombres",
                "pe.email",
                "pe.direccion",
                "pe.celular",
                "pe.numero_identidad",
                "pe.activo"
            )
            ->where("pe.eliminado", 0)
            ->catTipoPersonaId($catTipoPersonaId)
            ->busqueda($busqueda)
            ->orderBy("pe.nombres", "ASC")
            ->paginate($rowsPage);

        return [
            "pagination" => [
                "total" => $personas->total(),
                "current_page" => $personas->currentPage(),
                "per_page" => $personas->perPage(),
                "last_page" => $personas->lastPage(),
                "from" => $personas->firstItem(),
                "to" => $personas->lastItem(),
            ],
            "result" => $personas
        ];

    }

    /* @description STORE */
    public function store($request)
    {
        $persona = new Persona();
        $persona->cat_tipo_persona_id = $request->cat_tipo_persona_id;
        // SI ES NUEVO ASIGNAR POR DEFECTO EL DEFAULT.JPG DE LA FOTO
        $persona->foto = $this->fotoDefault;
        $persona->numero_identidad = $request->numero_identidad;
        $persona->nombres = $request->nombres;
        $persona->email = $request->email;
        $persona->celular = $request->celular ?? 0;
        $persona->direccion = $request->direccion;
        $persona->usuario_crea_id = Auth::id() ?? 1;
        $persona->save();
        // ACTUALIZAR LA FOTO
        $this->saveFoto($request, $persona);

        return $persona;
    }

    /* @description STORE */
    public function update($request)
    {

        $persona = Persona::findOrFail($request->persona_id);
        $persona->numero_identidad = $request->numero_identidad;
        $persona->nombres = $request->nombres;
        $persona->email = $request->email;
        $persona->celular = $request->celular ?? 0;
        $persona->direccion = $request->direccion;
        $persona->usuario_actualiza_id = Auth::id();
        $persona->fecha_actualiza = Carbon::now();
        $persona->save();

        // ACTUALIZAR LA FOTO
        $this->saveFoto($request, $persona);

        return $persona;
    }

    public function edit($request)
    {
        $persona = Persona::from("personas AS pe")
            ->select("pe.persona_id",
                "pe.numero_identidad",
                "pe.nombres",
                "pe.email",
                "pe.celular",
                "pe.direccion",
                "pe.cat_tipo_persona_id",
                "pe.activo",
            )
            ->where("pe.eliminado", 0)
            ->where("pe.persona_id", $request->persona_id)
            ->get();

        return $persona;
    }

    /* @description EXISTE el nombre y Tipo de Documentos */
    public function existePersona($request): bool
    {
        // FORMATEAR
        $numeroIdentidad = strtoupper(trim($request->numero_identidad));

        return Persona::where("eliminado", false)
            ->where("numero_identidad", "!=", 0)
            ->whereRaw("UPPER(numero_identidad) = ?", [$numeroIdentidad])
            ->notPersonaId($request->persona_id)
            ->exists();
    }

    // MÉTODOS PRIVADOS
    private function saveFoto($request, $persona)
    {
        // VALIDAR LA FOTO Y GUARDAR
        if ($request->hasFile("foto")) {
            $file = $request->file('foto');
            // EXTENCION
            $extension = $file->getClientOriginalExtension();
            // NOMBRE PERSONALZADO
            $foto = $persona->persona_id . '.' . $extension;

            // ELIMINAR EL ARCHIVO SI EXISTE
            if ($request->persona_id && $persona->logo !== $this->fotoDefault) {
                Storage::disk('public_path')->delete($persona->foto);
            }

            $persona->foto = Storage::disk("public_path")->putFileAs($this->fotoPath, $file, $foto);
            $persona->save();
        }
    }
}
