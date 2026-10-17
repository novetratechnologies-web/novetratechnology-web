<?php

use App\Models\Church;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('church_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('church_id');
            $table->foreign('church_id')->references('id')->on('church')->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('slug', 140);
            $table->text('description')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('timezone', 64)->default('Africa/Nairobi');
            $table->boolean('is_headquarters')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['church_id', 'slug']);
            $table->index(['church_id', 'is_active']);
        });

        foreach (Church::withTrashed()->orderBy('id')->get() as $church) {
            $branchId = DB::table('church_branches')->insertGetId([
                'church_id' => $church->id,
                'name' => 'Headquarters',
                'slug' => 'headquarters',
                'description' => 'Main church campus and central administration.',
                'address' => $church->address,
                'timezone' => 'Africa/Nairobi',
                'is_headquarters' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (Schema::hasTable('users')) {
                Schema::table('users', function (Blueprint $table) {
                    if (!Schema::hasColumn('users', 'branch_id')) {
                        $table->unsignedInteger('branch_id')->nullable()->after('church_id');
                        $table->foreign('branch_id')->references('id')->on('church_branches')->nullOnDelete();
                        $table->index(['church_id', 'branch_id']);
                    }
                });
                DB::table('users')->where('church_id', $church->id)->whereNull('branch_id')->update(['branch_id' => $branchId]);
            }
        }

        foreach (['events', 'galleries', 'sermons', 'posts', 'home_hero_banners', 'church_service_schedules'] as $tableName) {
            if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'branch_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->unsignedInteger('branch_id')->nullable()->after('church_id');
                $table->foreign('branch_id', $tableName . '_branch_id_foreign')
                    ->references('id')->on('church_branches')->nullOnDelete();
                $table->index(['church_id', 'branch_id'], $tableName . '_church_branch_idx');
            });
        }
    }

    public function down(): void
    {
        foreach (['events', 'galleries', 'sermons', 'posts', 'home_hero_banners', 'church_service_schedules'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropForeign($tableName . '_branch_id_foreign');
                    $table->dropIndex($tableName . '_church_branch_idx');
                    $table->dropColumn('branch_id');
                });
            }
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropIndex(['church_id', 'branch_id']);
                $table->dropColumn('branch_id');
            });
        }
        Schema::dropIfExists('church_branches');
    }
};
