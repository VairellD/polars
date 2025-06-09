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
        // Tabel hashtags
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // contoh: "kucing", "photography"
            $table->string('slug')->unique(); // untuk URL friendly
            $table->integer('posts_count')->default(0); // counter untuk optimasi
            $table->timestamps();

            $table->index('name');
            $table->index('posts_count');
        });

        // Tabel pivot untuk many-to-many relationship
        Schema::create('post_hashtags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('hashtag_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['post_id', 'hashtag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_hashtags');
        Schema::dropIfExists('hashtags');
    }
};

// Jalankan dengan:
// php artisan make:migration create_hashtags_table
// Copy code ini lalu: php artisan migrate
