<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('work_orders')) return;

        Schema::table('work_orders', function (Blueprint $t) {
            if (!Schema::hasColumn('work_orders','worker_response')) {
                $t->string('worker_response', 16)->default('pending'); // pending|accepted|declined
            }
            if (!Schema::hasColumn('work_orders','completion_code')) {
                $t->string('completion_code', 16)->nullable();
            }
            if (!Schema::hasColumn('work_orders','completed_at')) {
                $t->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('work_orders')) return;

        Schema::table('work_orders', function (Blueprint $t) {
            foreach (['completed_at','completion_code','worker_response'] as $c) {
                if (Schema::hasColumn('work_orders',$c)) $t->dropColumn($c);
            }
        });
    }
};
