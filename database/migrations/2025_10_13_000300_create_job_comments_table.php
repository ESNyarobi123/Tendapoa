<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('job_comments')) {
            Schema::create('job_comments', function (Blueprint $t) {
                $t->id();
                $t->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
                $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $t->text('message');
                $t->boolean('is_application')->default(false);
                $t->unsignedInteger('bid_amount')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_comments');
    }
};
