<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_chat_clears', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignUuid('admin_id')->constrained('profiles')->cascadeOnDelete();
            $table->timestampTz('cleared_at')->useCurrent();
            $table->timestampsTz();
            $table->unique(['chat_id', 'admin_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_chat_clears');
    }
};

