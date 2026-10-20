<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skor C6 (Kesesuaian Aktivitas) untuk setiap pasangan destinasi x minat.
 * Inilah yang membuat personalisasi mungkin: destinasi yang sama bisa
 * bernilai 5 untuk snorkeling tetapi 1 untuk diving.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skor_kesesuaian_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $table->string('minat', 30);
            $table->integer('skor'); // 1-5
            $table->timestamps();

            $table->unique(['destinasi_id', 'minat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_kesesuaian_aktivitas');
    }
};
