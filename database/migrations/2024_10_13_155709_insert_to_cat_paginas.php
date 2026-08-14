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
        // GENERAL
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (2, 'Ver Resultados', '/ver_resultados', 'mdi-trophy-outline', 2, 0, true, 1)");

        // DEL CLIENTE
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (3, 'Jugar', '/mi_lotto/jugar', 'mdi-bullseye-arrow', 3, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (4, 'Mi Lotto', '/mi_loto', 'mdi-dice-multiple-outline', 4, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (5, 'Mis Sorteos', '/mi_lotto/mis_sorteos', 'mdi-format-list-bulleted', 1, 4, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (6, 'Mis Premios', '/mi_lotto/mis_premios', 'mdi-list-box-outline', 2, 4, false, 1)");

        // DE LA AGENCIA
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (7, 'Agencias', '/agencias', 'mdi-storefront-outline', 5, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (8, 'Ventas', '/agencias/ventas', 'mdi-cart-variant', 1, 7, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (9, 'Clientes', '/agencias/clientes', 'mdi-crown-outline', 2, 7, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (10, 'Administradores', '/agencias/administradores', 'mdi-briefcase-account-outline', 3, 7, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (11, 'Vendedores', '/agencias/vendedores', 'mdi-badge-account-outline', 4, 7, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (12, 'Config', '/agencias/config', 'mdi-cogs', 5, 7, false, 1)");

        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (13, 'Control de Caja', '/cajas', 'mdi-cash-register', 6, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (14, 'Movimientos', '/cajas/movimientos', 'mdi-list-box-outline', 1, 13, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (15, 'Pagar Comisiones', '/cajas/pagar_comisiones', ' mdi-cash-100', 2, 13, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (16, 'Pagar Premios', '/cajas/pagar_premios', ' mdi-cash-100', 3, 13, false, 1)");

        // DEL VENDEDOR
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (17, 'Vender', '/vendedores/vender', 'mdi-cart-plus', 7, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (18, 'Control de Ventas', '/vendedores', 'mdi-monitor-dashboard', 8, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (19, 'Mis Ventas', '/vendedores/ventas', 'mdi-list-box-outline', 1, 18, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (20, 'Mis Comisiones', '/vendedores/comsiones', 'mdi-calculator-variant-outline', 2, 18, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (21, 'Pagar Premios', '/vendedores/pagar_premios', 'mdi-cash-100', 3, 18, false, 1)");

        // SUPER ADMINISTRADOR
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (22, 'Administración', '/admin', 'mdi-briefcase-arrow-left-right-outline', 9, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (23, 'Resultados Sorteos', '/admin/resultados_sorteos', 'mdi-format-list-numbered', 1, 22, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (24, 'Bloquear Sorteos', '/admin/bloquear_sorteos', 'mdi-lock-outline', 2, 22, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (25, 'Bloquear Numeros', '/admin/bloquear_numeros', 'mdi-lock-outline', 3, 22, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (26, 'Bloquear Clientes', '/admin/bloquear_clientes', 'mdi-lock-outline', 4, 22, false, 1)");

        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (27, 'Entidades', '/entidades', 'mdi-domain', 10, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (28, 'Clientes', '/entidades/clientes', 'mdi-crown-outline', 1, 27, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (29, 'Admnistradores', '/entidades/administradores', 'mdi-briefcase-account-outline', 2, 27, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (30, 'Vendedores', '/entidades/vendedores', 'mdi-badge-account-outline', 3, 27, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (31, 'Agencias', '/entidades/agencias', 'mdi-storefront-outline', 4, 27, false, 1)");

        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (32, 'Configuración', '/config', 'mdi-wrench-cog-outline', 11, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (33, 'Loterías', '/config/loterias', 'mdi-emoticon-excited-outline', 1, 32, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (34, 'Juegos', '/config/juegos', 'mdi-bullseye-arrow', 2, 32, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (35, 'Sorteos', '/config/sorteos', 'mdi-dice-multiple-outline', 3, 32, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (36, 'Parametros', '/config/params', 'mdi-tune-vertical', 4, 32, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (37, 'APP', '/config/app', 'mdi-network-pos', 5, 32, false, 1)");

        // GENERAL
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (38, 'Reportes', '/rptes', 'mdi-folder-table-outline', 12, 0, true, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (39, 'Ventas', '/rptes/agencias/ventas', 'mdi-file-table-outline', 1, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (40, 'Comisiones', '/rptes/agencias/comisiones', 'mdi-file-table-outline', 2, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (41, 'Premios', '/rptes/agencias/premios', 'mdi-file-table-outline', 3, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (42, 'Caja', '/rptes/agencias/caja', 'mdi-file-table-outline', 4, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (43, 'Mis Ventas', '/rptes/vendedores/ventas', 'mdi-file-table-outline', 5, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (44, 'Mis Comisiones', '/rptes/vendedores/comisiones', 'mdi-file-table-outline', 6, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (45, 'Mis Compras', '/rptes/clientes/compras', 'mdi-file-table-outline', 7, 38, false, 1)");
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (46, 'Mis Premios', '/rptes/clientes/premios', 'mdi-file-table-outline', 8, 38, false, 1)");

        // GENERAL
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (47, 'Ayuda', '/ayuda', 'mdi-help-circle-outline', 20, 0, true, 1)");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DO
    }
};
