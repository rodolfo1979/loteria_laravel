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
        // NEW ACCION
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (8, 'Pagar', 'mdi-cash-100', 2, 1)");
        DB::statement("INSERT INTO cat_acciones (cat_accion_id, nombre, icono, orden, usuario_crea_id) VALUES (9, 'Cobrar', 'mdi-cash-100', 2, 1)");


        // GENERAL
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(1, 7, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(2, 1, 1)");

        // DEL CLIENTE
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(3, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(4, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(5, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(5, 2, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(6, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(6, 9, 1)");

        // DE LA AGENCIA
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(7, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(8, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(8, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(8, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(8, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(9, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(9, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(9, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(9, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(10, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(10, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(10, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(10, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(11, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(11, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(11, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(11, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(12, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(12, 3, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(13, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(14, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(14, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(14, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(14, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(15, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(15, 8, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(15, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(15, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(16, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(16, 8, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(16, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(16, 5, 1)");

        // DEL VENDEDOR
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(17, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(18, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(19, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(19, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(19, 4, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(19, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(20, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(21, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(21, 8, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(21, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(21, 5, 1)");

        // DEL SUPER ADMINISTRADOR
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(22, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(23, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(23, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(23, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(23, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(24, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(24, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(24, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(24, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(25, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(25, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(25, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(25, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(26, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(26, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(26, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(26, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(27, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(28, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(28, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(28, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(28, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(29, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(29, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(29, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(29, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(30, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(30, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(30, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(30, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(31, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(31, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(31, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(31, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(32, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(33, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(33, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(33, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(33, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(34, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(34, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(34, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(34, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(35, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(35, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(35, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(35, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(36, 1, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(36, 2, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(36, 3, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(36, 5, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(37, 5, 1)");
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(37, 3, 1)");

        // REPORTES
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(38, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(39, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(40, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(41, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(42, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(43, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(44, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(45, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(46, 1, 1)");

        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(47, 1, 1)");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       // DO
    }
};
