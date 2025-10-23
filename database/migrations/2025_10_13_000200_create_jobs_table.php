<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // muhitaji
                $t->foreignId('category_id')->constrained('categories');
                $t->string('title');
                $t->text('description')->nullable();
                $t->unsignedInteger('price')->default(0); // TZS
                $t->decimal('lat', 10, 7);
                $t->decimal('lng', 10, 7);
                $t->string('address_text')->nullable();
                $t->enum('status', ['draft','posted','assigned','in_progress','completed','cancelled'])->default('draft');
                $t->foreignId('accepted_worker_id')->nullable()->constrained('users');
                $t->timestamp('published_at')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
