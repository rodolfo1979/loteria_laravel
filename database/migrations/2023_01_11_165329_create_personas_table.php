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
        Schema::create("personas", function (Blueprint $table) {
            $table->increments("persona_id");
            $table->string("numero_identidad", 20)->nullable()->index();
            $table->string("nombres", 100)->index();
            $table->string("email", 100)->nullable();
            $table->string("celular", 20)->nullable();
            $table->string("direccion", 255)->nullable();
            $table->string("foto", 100)->nullable();
            $table->unsignedInteger("cat_tipo_persona_id")->nullable();
            $table->foreign("cat_tipo_persona_id")->references("cat_tipo_persona_id")->on("cat_tipos_personas")->onDelete("no action")->onUpdate("cascade");
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        // INSERT DEVELOPER
        DB::statement("INSERT INTO personas (persona_id,numero_identidad, nombres, email, celular, direccion, cat_tipo_persona_id, usuario_crea_id)
            VALUES(1, '2812020', 'DEVELOPER', 'developer@system.com', '258-85222', 'Calle Real', 1, 1)");
        // INSERT EMPLEADO
        DB::statement("INSERT INTO personas (persona_id, numero_identidad, nombres, email, celular, direccion, cat_tipo_persona_id, usuario_crea_id)
            VALUES(2,'2812022', 'Persona Administrador', 'administrador@system.com', '258-85222', 'Calle Admnistrador', 2, 1)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("personas");
    }
};
