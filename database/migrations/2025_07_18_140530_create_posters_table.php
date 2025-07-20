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
        Schema::create('posters', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 50);
            $table->text('narasi');
            $table->string('gambar');
            $table->string('frame');
            $table->string('hasil_final')->nullable();
            $table->float('scale_gambar')->default(1.0); // Skala gambar (default: 1.0)
            $table->integer('pos_x')->default(0); // Posisi X gambar (default: 0)
            $table->integer('pos_y')->default(0); // Posisi Y gambar (default: 0)
            $table->integer('judul_narasi_gap')->default(300); // Jarak antara judul dan narasi (default: 300px)
            $table->integer('judul_y')->default(1600); // Posisi Y judul dari atas (default: 1600px)
            $table->integer('narasi_y')->default(1900); // Posisi Y narasi dari atas (default: 1900px)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posters');
    }
};