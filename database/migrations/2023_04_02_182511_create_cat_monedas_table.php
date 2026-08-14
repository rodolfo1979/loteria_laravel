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
        Schema::create("cat_monedas", function (Blueprint $table) {
            $table->increments("cat_moneda_id");
            $table->string("nombre", 50);
            $table->string("simbolo", 5);
            $table->string("codigo_iso", 5);
            $table->boolean("es_principal")->default(false);
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        // INSERTS
        DB::statement("INSERT INTO cat_monedas (cat_moneda_id, nombre, simbolo, codigo_iso, es_principal, usuario_crea_id) VALUES (1, 'Colón', '₡', 'CRC', true, 1)");
        DB::statement("INSERT INTO cat_monedas (cat_moneda_id, nombre, simbolo, codigo_iso, es_principal, usuario_crea_id) VALUES (2, 'Dólar', '$', 'USD', false, 1)");
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("cat_monedas");
    }
};
