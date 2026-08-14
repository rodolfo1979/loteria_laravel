<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("agencias", function (Blueprint $table) {
            $table->increments("agencia_id");
            $table->string("nombre_comercial", 100);
            $table->string("razon_social", 100);
            $table->string("rut", 20);
            $table->string("lema", 100);
            $table->string("email", 100);
            $table->string("telefonos", 50);
            $table->string("direccion", 150);
            $table->string("logo", 100);
            $table->boolean("es_propia")->default(false);
            $table->boolean("es_matriz")->default(false);
            $table->unsignedInteger("cat_moneda_id")->index();
            $table->foreign("cat_moneda_id")->references("cat_moneda_id")->on("cat_monedas")->onDelete("no action")->onUpdate("cascade");
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        // INSERTS
        DB::statement("INSERT INTO agencias (agencia_id, nombre_comercial, razon_social, rut, lema, email, telefonos, direccion, logo, cat_moneda_id, usuario_crea_id, es_propia, es_matriz) VALUES (1, 'Lotto Company', 'SERVICIOS VARIOS', 'J0310000236720', 'MEJOR SIEMPRE', 'ventas@lottomanager.com', '2293-7018 / 8681-7433 / 8419-7330', 'Calle de las Estrellas', '/images/logo.png', 1, 1, true, true)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("agencias");
    }
};
