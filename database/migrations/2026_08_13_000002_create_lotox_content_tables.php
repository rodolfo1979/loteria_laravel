<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('body');
            $table->boolean('active')->default(true);
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->jsonb('schedule')->default('{}');
            $table->timestampsTz();
            $table->index(['tenant_id', 'active']);
        });

        Schema::create('quick_replies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('label');
            $table->string('tag')->nullable();
            $table->text('body');
            $table->string('tag_color')->nullable();
            $table->string('tag_emoji')->nullable();
            $table->timestampsTz();
        });

        Schema::create('media_library', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('tag')->nullable();
            $table->text('image_url');
            $table->timestampsTz();
        });

        Schema::create('chat_read_markers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->timestampTz('last_read_message_at')->nullable();
            $table->timestampsTz();
            $table->unique(['chat_id', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_read_markers');
        Schema::dropIfExists('media_library');
        Schema::dropIfExists('quick_replies');
        Schema::dropIfExists('announcements');
    }
};

