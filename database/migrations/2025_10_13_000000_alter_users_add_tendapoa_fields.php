<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add columns only if missing
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users','phone')) {
                $t->string('phone')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users','role')) {
                $t->enum('role', ['admin','muhitaji','mfanyakazi'])->default('muhitaji')->after('phone');
            }
            if (!Schema::hasColumn('users','lat')) {
                $t->decimal('lat', 10, 7)->nullable()->after('role');
            }
            if (!Schema::hasColumn('users','lng')) {
                $t->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });

        // Ensure existing users have a role
        try {
            DB::table('users')->whereNull('role')->update(['role' => 'muhitaji']);
        } catch (\Throwable $e) {
            // ignore if column didn't exist before this point
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (Schema::hasColumn('users','lng')) $t->dropColumn('lng');
            if (Schema::hasColumn('users','lat')) $t->dropColumn('lat');
            if (Schema::hasColumn('users','role')) $t->dropColumn('role');
            if (Schema::hasColumn('users','phone')) $t->dropColumn('phone');
        });
    }
};
