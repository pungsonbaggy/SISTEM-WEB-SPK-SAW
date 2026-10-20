<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catatan setiap permintaan rekomendasi.
 * Dipakai untuk analisis sebaran rekomendasi (exposure) pada Bab IV:
 * destinasi mana yang sering muncul di lima besar untuk 36 kombinasi profil.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->string('sesi_id', 64)->index();
            $table->string('minat', 30);
            $table->string('anggaran', 20);
            $table->string('jarak', 20);
            $table->foreignId('destinasi_id')->constrained('destinasi');
            $table->integer('peringkat');
            $table->decimal('nilai_v', 6, 4);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_rekomendasi');
    }
};
