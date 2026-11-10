<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema tabel destinasi wisata bahari (A1–A14) dan nilai kriteria C1–C6.
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
            $table->decimal('c1_jarak', 6, 2);
            $table->decimal('c2_biaya', 10, 2);
            $table->integer('c3_fasilitas');
            $table->integer('c4_keindahan');
            $table->integer('c5_kebersihan');
            $table->integer('c6_aktivitas');
            $table->integer('total_fasilitas')->default(0);
            $table->integer('total_aktivitas')->default(0);
            $table->json('fasilitas_list')->nullable();
            $table->json('aktivitas_list')->nullable();
            $table->string('foto')->nullable();
            $table->json('foto_list')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi');
    }
};
