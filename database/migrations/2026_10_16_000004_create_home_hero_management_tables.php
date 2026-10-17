<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('home_hero_banners')) {
            Schema::create('home_hero_banners', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('church_id');
                $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
                $table->string('eyebrow', 100)->nullable();
                $table->string('title', 180);
                $table->string('highlight', 180)->nullable();
                $table->text('description')->nullable();
                $table->string('image_path', 500);
                $table->string('primary_label', 60)->nullable();
                $table->string('primary_url', 500)->nullable();
                $table->string('secondary_label', 60)->nullable();
                $table->string('secondary_url', 500)->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('display_order')->default(0);
                $table->enum('transition', ['fade', 'slide'])->default('fade');
                $table->unsignedTinyInteger('duration_seconds')->default(6);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
                $table->index(['church_id', 'is_active', 'display_order'], 'home_hero_banners_public_idx');
            });
        }

        if (!Schema::hasTable('church_live_streams')) {
            Schema::create('church_live_streams', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('church_id')->unique();
                $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
                $table->boolean('is_live')->default(false);
                $table->string('title', 180)->default('Join us live');
                $table->text('message')->nullable();
                $table->string('provider', 24)->default('youtube');
                $table->string('stream_url', 500)->nullable();
                $table->unsignedInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
                $table->timestamp('went_live_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('church_live_streams');
        Schema::dropIfExists('home_hero_banners');
    }
};
