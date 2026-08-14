<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blueprint::macro('estadosAuditoria', function (): void {
            $this->boolean('activo')->default(true);
            $this->boolean('eliminado')->default(false);
        });

        Blueprint::macro('usuariosAuditoria', function (): void {
            $this->unsignedInteger('usuario_crea_id');
            $this->foreign('usuario_crea_id')->references('usuario_id')->on('usuarios')->onDelete('no action')->onUpdate('cascade');
            $this->unsignedInteger('usuario_actualiza_id')->nullable();
            $this->foreign('usuario_actualiza_id')->references('usuario_id')->on('usuarios')->onDelete('no action')->onUpdate('cascade');
        });

        Blueprint::macro('fechasAuditoria', function (): void {
            $this->timestamp('fecha_crea')->useCurrent();
            $this->timestamp('fecha_actualiza')->nullable();
        });
    }
}

