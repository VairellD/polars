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
        // Tabel categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: "UI/UX Design", "Animation"
            $table->string('slug')->unique(); // contoh: "ui-ux-design", "animation"
            $table->string('description')->nullable();
            $table->string('icon')->nullable(); // bootstrap icon class
            $table->string('color', 7)->default('#007bff'); // hex color
            $table->integer('posts_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });

        // Update posts table untuk menambah category_id
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->index('category_id');
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['post_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_categories');
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};

// Jalankan dengan:
// php artisan make:migration create_categories_table
// Copy code ini lalu: php artisan migrate
