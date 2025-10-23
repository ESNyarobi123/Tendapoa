<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->enum('poster_type', ['muhitaji', 'mfanyakazi'])->default('muhitaji')->after('user_id');
            $table->decimal('posting_fee', 10, 2)->nullable()->after('poster_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['poster_type', 'posting_fee']);
        });
    }
};