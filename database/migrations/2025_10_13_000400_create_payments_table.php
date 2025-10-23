<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $t) {
                $t->id();
                $t->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
                $t->string('order_id')->unique();
                $t->unsignedInteger('amount');
                $t->enum('status', ['PENDING','IN_PROGRESS','COMPLETED','FAILED','CANCELLED'])->default('PENDING');
                $t->string('resultcode')->nullable();
                $t->string('reference')->nullable();
                $t->string('channel')->nullable();
                $t->string('msisdn')->nullable();
                $t->string('transid')->nullable();
                $t->json('meta')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
