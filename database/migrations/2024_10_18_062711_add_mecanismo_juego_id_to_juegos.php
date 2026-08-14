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
        Schema::table('juegos', function (Blueprint $table) {
            $table->unsignedInteger("mecanismo_juego_id")->index()->default(1);
            $table->foreign("mecanismo_juego_id")->references("mecanismo_juego_id")->on("mecanismos_juegos")->onDelete("no action")->onUpdate("cascade");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            //
        });
    }
};
