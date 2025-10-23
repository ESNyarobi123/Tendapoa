<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('jobs')) return;

        Schema::table('jobs', function (Blueprint $t) {
            if (!Schema::hasColumn('jobs','created_at')) {
                $t->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('jobs','updated_at')) {
                $t->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('jobs')) return;

        Schema::table('jobs', function (Blueprint $t) {
            if (Schema::hasColumn('jobs','updated_at')) $t->dropColumn('updated_at');
            if (Schema::hasColumn('jobs','created_at')) $t->dropColumn('created_at');
        });
    }
};
