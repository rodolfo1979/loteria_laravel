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
        Schema::create('paginas_acciones', function (Blueprint $table) {
            $table->increments("pagina_accion_id");
            $table->unsignedInteger("cat_pagina_id")->index();
            $table->foreign("cat_pagina_id")->references("cat_pagina_id")->on("cat_paginas")->onDelete("no action")->onUpdate("cascade");
            $table->unsignedInteger("cat_accion_id")->index();
            $table->foreign("cat_accion_id")->references("cat_accion_id")->on("cat_acciones")->onDelete("no action")->onUpdate("cascade");
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        // DASHBOARD
        DB::statement("INSERT INTO paginas_acciones (cat_pagina_id, cat_accion_id, usuario_crea_id) VALUES(1, 1, 1)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paginas_acciones');
    }
};
