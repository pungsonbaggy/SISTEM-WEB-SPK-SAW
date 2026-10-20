<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel destinasi = daftar alternatif (A1..A15) beserta skor kriteria C1..C5.
 * C6 tidak ada di sini karena nilainya berbeda untuk setiap minat
 * (lihat tabel skor_kesesuaian_aktivitas).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('c1_jarak', 5, 2);       // km
            $table->decimal('c2_biaya', 10, 2);      // ribu rupiah
            $table->integer('c3_fasilitas');         // skala 1-5
            $table->integer('c4_keindahan');         // skala 1-5
            $table->integer('c5_kebersihan');        // skala 1-5
            $table->unsignedInteger('kunjungan_2025');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi');
    }
};
