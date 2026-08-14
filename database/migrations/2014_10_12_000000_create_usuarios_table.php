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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('usuario_id');
            $table->string('usuario')->index();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedInteger("persona_id")->nullable()->index();
            $table->unsignedInteger("cat_rol_id")->nullable()->default(1);
            $table->estadosAuditoria();
            $table->unsignedInteger('usuario_crea_id')->nullable();
            $table->unsignedInteger('usuario_actualiza_id')->nullable();
            $table->fechasAuditoria();
        });

        // insert
        DB::statement("INSERT INTO usuarios (usuario, password, persona_id) VALUES('developer', '$2y$10$1/jLu9SNv3OmyhiyGJSXgexmk3Ucc3Gt.tkGj4ZQXPtEvlvL.hKnO', 1)");
        DB::statement("INSERT INTO usuarios (usuario, password, persona_id) VALUES('gestor', '$2y$10$1/jLu9SNv3OmyhiyGJSXgexmk3Ucc3Gt.tkGj4ZQXPtEvlvL.hKnO', 2)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
