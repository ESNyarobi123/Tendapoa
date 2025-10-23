<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $t->unsignedInteger('amount');
                $t->enum('status', ['PENDING','PROCESSING','PAID','REJECTED'])->default('PENDING');
                $t->string('method')->default('mobile_money');
                $t->string('account')->nullable(); // msisdn
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
