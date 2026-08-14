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
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments("venta_id");
            $table->string("numero", 30);
            $table->string("serie", 5)->nullable();
            $table->date("fecha_sorteo");
            $table->unsignedInteger("juego_id")->index();
            $table->foreign("juego_id")->references("juego_id")->on("juegos")->onDelete("no action")->onUpdate("cascade");
            $table->unsignedInteger("agencia_id")->index();
            $table->foreign("agencia_id")->references("agencia_id")->on("agencias")->onDelete("no action")->onUpdate("cascade");
            $table->unsignedInteger("cliente_id")->index();
            $table->foreign("cliente_id")->references("persona_id")->on("personas")->onDelete("no action")->onUpdate("cascade");
            $table->unsignedInteger("vendedor_id")->nullable()->index();
            $table->foreign("vendedor_id")->references("persona_id")->on("personas")->onDelete("no action")->onUpdate("cascade");
            $table->string("observacion", 250)->nullable();
            $table->double("comision_porcentaje", 5, 2)->default(0);
            $table->double("total", 22, 2);
            $table->string("ip_address", 50)->nullable();
            $table->string("user_agent", 50)->nullable();
            $table->string("latitude", 50)->nullable();
            $table->string("longitude", 50)->nullable();
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
        Schema::dropIfExists('ventas');
    }
};
