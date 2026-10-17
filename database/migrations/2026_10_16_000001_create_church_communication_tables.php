<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('communication_preferences')) {
            Schema::create('communication_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->unique();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->boolean('email_opt_in')->default(false);
                $table->timestamp('email_consent_at')->nullable();
                $table->string('email_consent_source', 32)->nullable();
                $table->boolean('sms_opt_in')->default(false);
                $table->timestamp('sms_consent_at')->nullable();
                $table->string('sms_consent_source', 32)->nullable();
                $table->boolean('in_app_enabled')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('church_service_schedules')) {
            Schema::create('church_service_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('church_id');
                $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
                $table->unsignedTinyInteger('day_of_week')->default(0)->comment('0=Sunday through 6=Saturday');
                $table->time('service_time');
                $table->string('timezone', 64)->default('Africa/Nairobi');
                $table->unsignedSmallInteger('reminder_minutes_before')->default(120);
                $table->boolean('is_active')->default(false);
                $table->timestamps();
                $table->index(['is_active', 'day_of_week'], 'service_schedule_active_day_idx');
            });
        }

        if (!Schema::hasIndex('church_service_schedules', 'service_schedule_church_day_time_uq')) {
            Schema::table('church_service_schedules', function (Blueprint $table) {
                $table->unique(['church_id', 'day_of_week', 'service_time'], 'service_schedule_church_day_time_uq');
            });
        }

        if (!Schema::hasTable('communication_deliveries')) {
            Schema::create('communication_deliveries', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('church_id');
                $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->string('event_type', 64);
                $table->unsignedInteger('event_id')->nullable();
                $table->date('occurrence_date')->nullable();
                $table->string('channel', 24);
                $table->string('recipient', 255);
                $table->string('idempotency_key', 191)->unique();
                $table->string('status', 24)->default('queued');
                $table->json('payload');
                $table->string('provider_message_id')->nullable();
                $table->text('failure_reason')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
                $table->index(['church_id', 'event_type', 'created_at'], 'communication_event_created_idx');
                $table->index(['status', 'scheduled_at'], 'communication_status_scheduled_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_deliveries');
        Schema::dropIfExists('church_service_schedules');
        Schema::dropIfExists('communication_preferences');
    }
};
