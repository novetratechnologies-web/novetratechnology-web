<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('groups') && !Schema::hasColumn('groups', 'branch_id')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->unsignedInteger('branch_id')->nullable()->after('church_id');
                $table->foreign('branch_id', 'groups_branch_id_foreign')->references('id')->on('church_branches')->nullOnDelete();
                $table->index(['church_id', 'branch_id'], 'groups_church_branch_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('groups') && Schema::hasColumn('groups', 'branch_id')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->dropForeign('groups_branch_id_foreign');
                $table->dropIndex('groups_church_branch_idx');
                $table->dropColumn('branch_id');
            });
        }
    }
};
