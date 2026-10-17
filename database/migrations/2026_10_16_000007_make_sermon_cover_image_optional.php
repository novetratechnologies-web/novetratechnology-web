<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sermons') && Schema::hasColumn('sermons', 'cover_image')) {
            Schema::table('sermons', function (Blueprint $table) {
                $table->string('cover_image')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Existing text-only sermons have no cover image; keeping the column nullable
        // avoids making rollback fail on those records.
    }
};
