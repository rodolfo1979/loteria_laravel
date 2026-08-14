<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('sorteos_dias');
        Schema::dropIfExists('sorteos_horas');
        Schema::dropIfExists('sorteos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
