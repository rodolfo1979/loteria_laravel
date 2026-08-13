<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_catalog', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('name');
            $table->unsignedInteger('user_limit');
            $table->jsonb('features')->default('[]');
            $table->unsignedInteger('monthly_price')->default(0);
            $table->boolean('active')->default(true);
            $table->timestampsTz();
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('active');
            $table->string('billing_status')->default('trial');
            $table->string('plan_code')->default('starter')->index();
            $table->unsignedInteger('extra_user_slots')->default(0);
            $table->string('logo_url')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->timestampTz('plan_started_at')->nullable();
            $table->timestampTz('plan_expires_at')->nullable();
            $table->string('ai_provider')->default('openai');
            $table->string('ai_image_provider')->default('openai');
            $table->timestampsTz();
            $table->foreign('plan_code')->references('code')->on('plan_catalog');
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('full_name')->nullable();
            $table->string('admin_alias')->nullable();
            $table->jsonb('admin_tags')->default('[]');
            $table->string('avatar_url')->nullable();
            $table->string('role')->default('client')->index();
            $table->string('status')->default('pending')->index();
            $table->timestampTz('last_seen_at')->nullable();
            $table->timestampsTz();
            $table->unique(['tenant_id', 'email']);
            $table->unique(['tenant_id', 'phone']);
        });

        Schema::create('chats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('type')->default('direct');
            $table->uuid('created_by')->nullable();
            $table->timestampsTz();
            $table->index(['tenant_id', 'updated_at']);
        });

        Schema::create('chat_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->string('role')->default('member');
            $table->timestampTz('joined_at')->useCurrent();
            $table->timestampsTz();
            $table->unique(['chat_id', 'profile_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignUuid('sender_id')->constrained('profiles')->cascadeOnDelete();
            $table->text('body')->nullable();
            $table->string('message_type')->default('text');
            $table->text('attachment_url')->nullable();
            $table->string('attachment_name')->nullable();
            $table->timestampsTz();
            $table->index(['tenant_id', 'chat_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chat_members');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('plan_catalog');
    }
};

