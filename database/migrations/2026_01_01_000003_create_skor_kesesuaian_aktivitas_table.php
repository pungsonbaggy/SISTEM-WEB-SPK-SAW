<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema tabel skor kesesuaian aktivitas (C6) untuk setiap kombinasi destinasi dan minat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skor_kesesuaian_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $table->string('minat', 30);
            $table->integer('skor');
            $table->timestamps();

            $table->unique(['destinasi_id', 'minat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_kesesuaian_aktivitas');
    }
};
