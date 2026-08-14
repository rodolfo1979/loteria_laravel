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
        Schema::create('roles_paginas_acciones', function (Blueprint $table) {
            $table->increments("rol_pagina_accion_id");
            $table->unsignedInteger("cat_rol_id")->index();
            $table->foreign("cat_rol_id")->references("cat_rol_id")->on("cat_roles")->onDelete("no action")->onUpdate("cascade");
            $table->unsignedInteger("pagina_accion_id")->index();
            $table->foreign("pagina_accion_id")->references("pagina_accion_id")->on("paginas_acciones")->onDelete("no action")->onUpdate("cascade");
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
        Schema::dropIfExists('roles_paginas_acciones');
    }
};
