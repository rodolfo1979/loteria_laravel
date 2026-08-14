<?php

namespace App\Services;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UsuarioService
{

    /**
     * @description STORE
     */
    public function store($request)
    {

        $usuario = new Usuario();
        $usuario->persona_id = $request->persona_id;
        $usuario->cat_rol_id = $request->cat_rol_id;
        $usuario->usuario = $request->usuario;
        $usuario->password = bcrypt($request->password);
        $usuario->usuario_crea_id = Auth::id();
        $usuario->save();

        return $usuario;

    }

    /**
     * @description PDATE
     */
    public function update($request)
    {

        $usuario = Usuario::findOrFail($request->usuario_id);

        // VER SI VIENE LA CONTRASEÑA PARA ACTUALIZARLA
        if ($request->password !== "" && !is_null($request->password)) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->cat_rol_id = $request->cat_rol_id;
        $usuario->usuario = $request->usuario;
        $usuario->usuario_actualiza_id = Auth::id();
        $usuario->fecha_actualiza = Carbon::now();
        $usuario->save();

        return $usuario;
    }

    /**
     * @description EXISTE nick de usuario
     */
    public function existeUsuario($request): bool
    {
        // FORMATEAR
        $user = strtoupper(trim($request->usuario));

        return Usuario::where("eliminado", false)
            ->whereRaw("UPPER(usuario) = ?", [$user])
            ->whereNot("usuario_id", $request->usuario_id)
            ->exists();;
    }

}
