<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cat_roles', function (Blueprint $table) {
            $table->increments("cat_rol_id");
            $table->string("nombre", 50);
            $table->string("descripcion", 250)->nullable();
            $table->boolean("modificable")->default(true);
            $table->unsignedInteger("cat_tipo_persona_id")->index();
            $table->foreign("cat_tipo_persona_id")->references("cat_tipo_persona_id")->on("cat_tipos_personas")->onDelete("no action")->onUpdate("cascade");
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();
        });

        DB::statement("INSERT INTO cat_roles (cat_rol_id, nombre, descripcion, modificable,  usuario_crea_id, cat_tipo_persona_id) VALUES(1, 'Super Administrador', 'Administrador DEL TODO', false, 1, 2)");
        DB::statement("INSERT INTO cat_roles (cat_rol_id, nombre, descripcion, modificable, usuario_crea_id, cat_tipo_persona_id) VALUES(2, 'Cliente', 'Solo lo que el cliente', false,  1, 4)");
        DB::statement("INSERT INTO cat_roles (cat_rol_id, nombre, descripcion, usuario_crea_id, cat_tipo_persona_id) VALUES(3, 'Administrador', 'Administrador Tienda', 1, 3)");
        DB::statement("INSERT INTO cat_roles (cat_rol_id, nombre, descripcion, usuario_crea_id, cat_tipo_persona_id) VALUES(4, 'Vendedor', 'Accesos Vendedor', 1, 3)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_roles');
    }
};
