<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add column if missing
        if (!Schema::hasColumn('work_orders', 'mfanyakazi_response')) {
            Schema::table('work_orders', function (Blueprint $table) {
                $table->string('mfanyakazi_response', 20)->nullable()->after('accepted_worker_id');
            });
        }

        // Copy old data from worker_response → mfanyakazi_response if both exist
        if (Schema::hasColumn('work_orders', 'worker_response')
            && Schema::hasColumn('work_orders', 'mfanyakazi_response')) {
            DB::statement("
                UPDATE work_orders
                SET mfanyakazi_response = worker_response
                WHERE (mfanyakazi_response IS NULL OR mfanyakazi_response = '')
            ");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('work_orders', 'mfanyakazi_response')) {
            Schema::table('work_orders', function (Blueprint $table) {
                $table->dropColumn('mfanyakazi_response');
            });
        }
    }
};
