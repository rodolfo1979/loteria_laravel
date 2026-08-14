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
        Schema::create('ventas_detalles', function (Blueprint $table) {
            $table->increments("venta_detalle_id");
            $table->unsignedInteger("venta_id")->index();
            $table->foreign("venta_id")->references("venta_id")->on("ventas")->onDelete("no action")->onUpdate("cascade");
            $table->string("numero", 30)->index();
            $table->time("hora")->index();
            $table->unsignedInteger("juego_forma_ganar_id")->index();
            $table->double("monto", 22, 2);
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_detalles');
    }
};
