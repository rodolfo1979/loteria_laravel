<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('country')->default('CR');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestampsTz();
            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'active', 'sort_order']);
        });

        Schema::create('lottery_draws', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('lottery_id')->constrained('lotteries')->cascadeOnDelete();
            $table->string('name');
            $table->time('draw_time')->nullable();
            $table->string('timezone')->default('America/Costa_Rica');
            $table->jsonb('days_of_week')->default('[]');
            $table->string('status')->default('active');
            $table->unsignedInteger('closes_before_minutes')->default(10);
            $table->timestampsTz();
            $table->index(['tenant_id', 'lottery_id', 'status']);
        });

        Schema::create('lottery_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('lottery_draw_id')->constrained('lottery_draws')->cascadeOnDelete();
            $table->date('result_date');
            $table->jsonb('winning_numbers')->default('[]');
            $table->string('source')->nullable();
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();
            $table->unique(['lottery_draw_id', 'result_date']);
            $table->index(['tenant_id', 'result_date']);
        });

        Schema::create('lottery_number_inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('lottery_draw_id')->constrained('lottery_draws')->cascadeOnDelete();
            $table->string('number', 8);
            $table->string('label')->nullable();
            $table->string('status')->default('available');
            $table->foreignUuid('reserved_by')->nullable()->constrained('profiles')->nullOnDelete();
            $table->timestampTz('reserved_until')->nullable();
            $table->jsonb('metadata')->default('{}');
            $table->timestampsTz();
            $table->unique(['lottery_draw_id', 'number']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lottery_number_inventory');
        Schema::dropIfExists('lottery_results');
        Schema::dropIfExists('lottery_draws');
        Schema::dropIfExists('lotteries');
    }
};

