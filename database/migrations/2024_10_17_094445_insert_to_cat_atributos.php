<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (1, 'NUMEROS', 'TIPOS_DATOS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (2, 'FECHAS', 'TIPOS_DATOS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (3, 'EXACTO', 'CALCULOS_JUGADAS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (4, 'PRIMERO', 'CALCULOS_JUGADAS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (5, 'TERMINACION', 'CALCULOS_JUGADAS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (6, 'REVERSIBLE', 'CALCULOS_JUGADAS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (7, 'ORDEN', 'CALCULOS_JUGADAS', 1)");
        DB::statement("INSERT INTO cat_atributos (cat_atributo_id, valor1, aplicacion1, usuario_crea_id) VALUES (8, 'DESORDEN', 'CALCULOS_JUGADAS', 1)");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DO SOME
    }
};
