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
        Schema::create("cat_paginas", function (Blueprint $table) {
            $table->increments("cat_pagina_id");
            $table->string("nombre", 150)->index();
            $table->string("slug", 150)->index();
            $table->string("icono", 50)->nullable()->index();
            $table->smallInteger("orden")->nullable();
            $table->unsignedInteger("cat_padre_id")->nullable();
            $table->boolean("es_menu", 1)->default(0);
            $table->boolean("solo_dev")->default(0);
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        // DASHBOARD
        DB::statement("INSERT INTO cat_paginas (cat_pagina_id, nombre, slug, icono, orden, cat_padre_id, es_menu, usuario_crea_id) VALUES (1, 'Dashboard', '/', 'mdi-apps', 1, null, true, 1)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("cat_paginas");
    }
};
