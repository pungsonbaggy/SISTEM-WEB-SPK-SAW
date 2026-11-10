<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema tabel bobot kriteria (w1–w6) per segmen profil wisatawan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bobot_segmen', function (Blueprint $table) {
            $table->id();
            $table->string('segmen', 30);
            $table->string('nilai', 30);
            $table->decimal('w1', 6, 4);
            $table->decimal('w2', 6, 4);
            $table->decimal('w3', 6, 4);
            $table->decimal('w4', 6, 4);
            $table->decimal('w5', 6, 4);
            $table->decimal('w6', 6, 4);
            $table->timestamps();

            $table->unique(['segmen', 'nilai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot_segmen');
    }
};
