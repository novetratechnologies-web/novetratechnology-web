<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('communication_campaigns')) {
            Schema::create('communication_campaigns', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('church_id');
                $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
                $table->unsignedInteger('created_by')->nullable();
                $table->string('title', 191);
                $table->text('body');
                $table->string('event_name', 191)->nullable();
                $table->timestamp('event_at')->nullable();
                $table->string('audience', 20)->default('all');
                $table->json('channels');
                $table->string('timezone', 64)->default('UTC');
                $table->timestamp('scheduled_at');
                $table->string('status', 20)->default('scheduled');
                $table->unsignedInteger('queued_deliveries')->default(0);
                $table->timestamp('processed_at')->nullable();
                $table->text('failure_reason')->nullable();
                $table->timestamps();
                $table->index(['status', 'scheduled_at'], 'communication_campaign_due_idx');
                $table->index(['church_id', 'created_at'], 'communication_campaign_church_idx');
            });
        }

        if (!Schema::hasTable('communication_templates')) {
            Schema::create('communication_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('church_id');
                $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
                $table->unsignedInteger('created_by')->nullable();
                $table->string('name', 100);
                $table->string('category', 40)->default('general');
                $table->string('title', 191);
                $table->text('body');
                $table->json('channels')->nullable();
                $table->timestamps();
                $table->index(['church_id', 'category'], 'communication_template_category_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_templates');
        Schema::dropIfExists('communication_campaigns');
    }
};
