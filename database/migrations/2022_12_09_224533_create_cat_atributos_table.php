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
        Schema::create('cat_atributos', function (Blueprint $table) {
            $table->increments("cat_atributo_id");
            $table->string("valor1", 100);
            $table->string("valor2", 100)->nullable();
            $table->string("aplicacion1", 100);
            $table->string("aplicacion2", 100)->nullable();
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_atributos');
    }
};
