<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sorteos', function (Blueprint $table) {
            $table->increments("sorteo_id");
            $table->unsignedInteger("juego_id")->index();
            $table->foreign("juego_id")->references("juego_id")->on("juegos")->onDelete("no action")->onUpdate("cascade");
            $table->string("alias", 100);
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sorteos');
    }
};
