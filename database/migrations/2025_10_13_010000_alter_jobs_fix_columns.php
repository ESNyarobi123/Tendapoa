<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('jobs')) {
            // Kama table haipo (rare), acha hii migration ipite kimya.
            return;
        }

        Schema::table('jobs', function (Blueprint $t) {
            // Muhitaji (owner)
            if (!Schema::hasColumn('jobs', 'user_id')) {
                // nullable ili kuepuka shida kama kuna rows tayari
                $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            }

            // Category
            if (!Schema::hasColumn('jobs', 'category_id')) {
                $t->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            }

            // Lat/Lng (OpenStreetMap)
            if (!Schema::hasColumn('jobs', 'lat')) {
                $t->decimal('lat', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('jobs', 'lng')) {
                $t->decimal('lng', 10, 7)->nullable();
            }

            // Address text (hiari)
            if (!Schema::hasColumn('jobs', 'address_text')) {
                $t->string('address_text')->nullable();
            }

            // Status (string badala ya ENUM ili iwe rahisi kubadilika)
            if (!Schema::hasColumn('jobs', 'status')) {
                $t->string('status', 32)->default('draft');
            }

            // Accepted worker
            if (!Schema::hasColumn('jobs', 'accepted_worker_id')) {
                $t->foreignId('accepted_worker_id')->nullable()->constrained('users')->nullOnDelete();
            }

            // Published timestamp
            if (!Schema::hasColumn('jobs', 'published_at')) {
                $t->timestamp('published_at')->nullable();
            }

            // Title/Description/Price kama zinakosekana (ili dashboard/feeds zifanye kazi)
            if (!Schema::hasColumn('jobs', 'title')) {
                $t->string('title')->nullable();
            }
            if (!Schema::hasColumn('jobs', 'description')) {
                $t->text('description')->nullable();
            }
            if (!Schema::hasColumn('jobs', 'price')) {
                $t->unsignedInteger('price')->default(0);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('jobs')) return;

        Schema::table('jobs', function (Blueprint $t) {
            // Ondoa FKs salama kisha columns
            foreach (['accepted_worker_id','category_id','user_id'] as $fk) {
                if (Schema::hasColumn('jobs', $fk)) {
                    try { $t->dropForeign([$fk]); } catch (\Throwable $e) {}
                }
            }

            foreach ([
                'accepted_worker_id','published_at','status','address_text',
                'lng','lat','category_id','user_id',
                // optional fields we added defensively
                'price','description','title',
            ] as $col) {
                if (Schema::hasColumn('jobs', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
    }
};
