<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('communication_campaigns', 'recurrence')) {
            Schema::table('communication_campaigns', function (Blueprint $table) {
                $table->string('recurrence', 16)->default('once');
            });
        }
        if (!Schema::hasColumn('communication_campaigns', 'repeat_interval')) {
            Schema::table('communication_campaigns', function (Blueprint $table) {
                $table->unsignedSmallInteger('repeat_interval')->default(1);
            });
        }
        if (!Schema::hasColumn('communication_campaigns', 'repeat_until')) {
            Schema::table('communication_campaigns', function (Blueprint $table) {
                $table->date('repeat_until')->nullable();
            });
        }
        if (!Schema::hasColumn('communication_campaigns', 'run_count')) {
            Schema::table('communication_campaigns', function (Blueprint $table) {
                $table->unsignedInteger('run_count')->default(0);
            });
        }
    }

    public function down(): void
    {
        $columns = array_values(array_filter(['recurrence', 'repeat_interval', 'repeat_until', 'run_count'], fn ($column) => Schema::hasColumn('communication_campaigns', $column)));
        if ($columns) {
            Schema::table('communication_campaigns', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
