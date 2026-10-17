<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('userprofiles', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'transgender'])->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        if (DB::table('userprofiles')->whereNull('gender')->exists()) {
            throw new RuntimeException('Cannot make user profile gender required while incomplete profile records exist.');
        }

        Schema::table('userprofiles', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'transgender'])->nullable(false)->change();
        });
    }
};
