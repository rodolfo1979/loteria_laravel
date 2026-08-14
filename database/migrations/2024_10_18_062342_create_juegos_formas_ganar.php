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
        Schema::create('juegos_formas_ganar', function (Blueprint $table) {
            $table->increments("juego_forma_ganar_id");
            $table->unsignedInteger("juego_id")->index();
            $table->foreign("juego_id")->references("juego_id")->on("juegos")->onDelete("no action")->onUpdate("cascade");
            $table->string("modalidad", 30);
            $table->string("ejemplo", 255)->nullable();
            $table->decimal("premio_veces");
            $table->unsignedInteger("calculo_jugada_id")->index();
            $table->foreign("calculo_jugada_id")->references("cat_atributo_id")->on("cat_atributos")->onDelete("no action")->onUpdate("cascade");
            $table->unsignedInteger("juego_forma_ganar_padre_id")->default(0);
            $table->smallInteger("orden_listado");
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
        Schema::dropIfExists('juegos_formas_ganar');
    }
};
