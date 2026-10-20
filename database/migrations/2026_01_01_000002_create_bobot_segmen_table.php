<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bobot kriteria per segmen profil (Tabel 3.10 skripsi).
 * Satu baris = satu nilai segmen, misal segmen=minat nilai=Snorkeling.
 * w1..w6 harus berjumlah 1.0000.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bobot_segmen', function (Blueprint $table) {
            $table->id();
            $table->string('segmen', 30);   // minat | anggaran | jarak
            $table->string('nilai', 30);    // Snorkeling | Rendah | Dekat | ...
            $table->decimal('w1', 6, 4);
            $table->decimal('w2', 6, 4);
            $table->decimal('w3', 6, 4);
            $table->decimal('w4', 6, 4);
            $table->decimal('w5', 6, 4);
            $table->decimal('w6', 6, 4);
            $table->timestamps();

            // satu kombinasi segmen+nilai hanya boleh ada satu baris
            $table->unique(['segmen', 'nilai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot_segmen');
    }
};
