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
        Schema::create("cat_acciones", function (Blueprint $table) {
            $table->increments("cat_accion_id");
            $table->string("nombre", 150)->index();
            $table->string("icono", 50)->index();
            $table->smallInteger("orden");
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        // INSERTS
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (1, 'Ver', 'mdi-eye-outline', 1, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (2, 'Crear', 'mdi-plus', 2, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (3, 'Editar', 'mdi-pencil-outline', 3, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (4, 'Eliminar', 'mdi-trash-can-outline', 4, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (5, 'Anular', 'mdi-cancel', 5, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (6, 'Datos acceso', 'mdi-key-chain-variant', 6, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (7, 'Info Dashboard', 'mdi-key-chain-variant', 7, 1)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("cat_acciones");
    }
};
