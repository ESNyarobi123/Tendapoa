<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payments')) return;

        if (Schema::hasColumn('payments','job_id')) {
            // Jaribu kuondoa FK kama ipo
            Schema::table('payments', function (Blueprint $t) {
                try { $t->dropForeign(['job_id']); } catch (\Throwable $e) {}
            });
            // Badilisha kuwa NULL bila kuhitaji doctrine/dbal (raw SQL)
            try {
                DB::statement('ALTER TABLE `payments` MODIFY `job_id` BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {
                // Kama server haikubali MODIFY, tuondoe kabisa hiyo column
                Schema::table('payments', function (Blueprint $t) {
                    $t->dropColumn('job_id');
                });
            }
        }
    }

    public function down(): void
    {
        // Hatutarudisha NOT NULL (si salama). Ukihitaji kurudisha,
        // unaweza kuongeza kolamu tena kwa mkono.
    }
};
