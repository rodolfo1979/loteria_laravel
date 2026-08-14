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
        Schema::create('mecanismos_juegos', function (Blueprint $table) {
            $table->increments("mecanismo_juego_id");
            $table->string("nombre", 100);
            $table->string("descripcion", 250)->nullable();
            $table->unsignedInteger("tipo_dato_id")->index();
            $table->foreign("tipo_dato_id")->references("cat_atributo_id")->on("cat_atributos")->onDelete("no action")->onUpdate("cascade");
            $table->string("valor_inicio", 20)->default(0);
            $table->string("valor_fin", 20)->default(0);
            $table->smallInteger("campos_tipo_dato")->default(0);
            $table->estadosAuditoria();
            $table->usuariosAuditoria();
            $table->fechasAuditoria();;
        });

        DB::statement("INSERT INTO mecanismos_juegos (mecanismo_juego_id, nombre, descripcion, tipo_dato_id, valor_inicio, valor_fin, campos_tipo_dato, usuario_crea_id)
        VALUES (1, 'MODO TIEMPOS (REVENTADOS)', 'SE ELIGEN 1 NUMERO DEL 00 A 99', 1, '00', '99', 1, 1)");
        DB::statement("INSERT INTO mecanismos_juegos (mecanismo_juego_id, nombre, descripcion, tipo_dato_id, valor_inicio, valor_fin, campos_tipo_dato, usuario_crea_id)
        VALUES (2, 'MODO 3 MONAZOS (3 MONAZOS)', 'SE ELIGE 3 NUMEROS  DEL 0 A 9', 1, '0', '9', 3, 1)");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mecanismos_juegos');
    }
};
