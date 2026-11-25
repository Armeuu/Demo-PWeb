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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('tipe'); // Anime, Manga, Drama
            $table->string('genre')->nullable();
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('tahun');
            $table->string('status')->default('Selesai');
            $table->integer('episode')->nullable();
            $table->integer('chapter')->nullable();
            $table->string('studio')->nullable();
            $table->string('penulis')->nullable();
            $table->string('network')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
