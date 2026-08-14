<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('juegos', function (Blueprint $table) {
            $table->increments("juego_id");
            $table->unsignedInteger("loteria_id")->index();
            $table->foreign("loteria_id")->references("loteria_id")->on("loterias")->onDelete("no action")->onUpdate("cascade");
            $table->string("nombre", 100);
            $table->string("descripcion", 100)->nullable();
            $table->string("logo", 100);
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
        Schema::dropIfExists('juegos');
    }
};
