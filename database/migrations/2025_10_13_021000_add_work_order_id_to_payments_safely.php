<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payments')) return;

        // Add column if missing
        Schema::table('payments', function (Blueprint $t) {
            if (!Schema::hasColumn('payments','work_order_id')) {
                $t->unsignedBigInteger('work_order_id')->nullable()->after('id');
            }
        });

        // Add FK if possible (ignore if already exists)
        Schema::table('payments', function (Blueprint $t) {
            if (Schema::hasColumn('payments','work_order_id') && Schema::hasTable('work_orders')) {
                try {
                    $t->foreign('work_order_id')->references('id')->on('work_orders')->nullOnDelete();
                } catch (\Throwable $e) {
                    // ignore if FK already added or name clash
                }
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('payments')) return;

        Schema::table('payments', function (Blueprint $t) {
            if (Schema::hasColumn('payments','work_order_id')) {
                try { $t->dropForeign(['work_order_id']); } catch (\Throwable $e) {}
                $t->dropColumn('work_order_id');
            }
        });
    }
};
