<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("cat_tipos_personas", function (Blueprint $table) {
            $table->increments("cat_tipo_persona_id");
            $table->string("nombre", 150);
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        DB::statement("INSERT INTO cat_tipos_personas (cat_tipo_persona_id, nombre, usuario_crea_id) VALUES(1, 'Desarrollador', 1)");
        DB::statement("INSERT INTO cat_tipos_personas (cat_tipo_persona_id, nombre, usuario_crea_id) VALUES(2, 'Super Administrador', 1)");
        DB::statement("INSERT INTO cat_tipos_personas (cat_tipo_persona_id, nombre, usuario_crea_id) VALUES(3, 'Empleado', 1)");
        DB::statement("INSERT INTO cat_tipos_personas (cat_tipo_persona_id, nombre, usuario_crea_id) VALUES(4, 'Cliente', 1)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("cat_tipos_personas");
    }
};
