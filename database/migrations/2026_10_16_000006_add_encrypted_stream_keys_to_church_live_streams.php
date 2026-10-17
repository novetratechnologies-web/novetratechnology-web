<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('church_live_streams') && !Schema::hasColumn('church_live_streams', 'stream_key')) {
            Schema::table('church_live_streams', function (Blueprint $table) {
                $table->text('stream_key')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('church_live_streams') && Schema::hasColumn('church_live_streams', 'stream_key')) {
            Schema::table('church_live_streams', function (Blueprint $table) {
                $table->dropColumn('stream_key');
            });
        }
    }
};
