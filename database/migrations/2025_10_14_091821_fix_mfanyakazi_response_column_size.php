<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check if the column exists and its current size
        $columns = DB::select("SHOW COLUMNS FROM work_orders LIKE 'mfanyakazi_response'");
        
        if (count($columns) > 0) {
            // Drop the column and recreate it with proper size
            Schema::table('work_orders', function (Blueprint $table) {
                $table->dropColumn('mfanyakazi_response');
            });
        }
        
        // Recreate with proper size
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('mfanyakazi_response', 100)->nullable()->after('accepted_worker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('mfanyakazi_response');
        });
    }
};