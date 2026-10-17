<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('donations')
            ->select('church_id', 'method', 'gateway_ref')
            ->whereNotNull('gateway_ref')
            ->groupBy('church_id', 'method', 'gateway_ref')
            ->havingRaw('COUNT(*) > 1')
            ->limit(1)
            ->exists();

        if ($duplicates) {
            throw new RuntimeException(
                'Cannot enforce donation payment idempotency: duplicate church/method/gateway_ref records exist. Resolve those records before retrying this migration.'
            );
        }

        Schema::table('donations', function (Blueprint $table) {
            $table->unique(['church_id', 'method', 'gateway_ref'], 'donations_verified_reference_unique');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique('donations_verified_reference_unique');
        });
    }
};
