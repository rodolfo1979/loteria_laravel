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
        Schema::create('sorteos_dias', function (Blueprint $table) {
            $table->increments("sorteo_dia_id");
            $table->unsignedInteger("sorteo_id")->index();
            $table->foreign("sorteo_id")->references("sorteo_id")->on("sorteos")->onDelete("no action")->onUpdate("cascade");
            $table->smallInteger("dia");
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
        Schema::dropIfExists('sorteos_dias');
    }
};
