<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aturan kelayakan: batas skor C6 minimum agar destinasi boleh dipertimbangkan.
 * Contoh: minat Diving, kriteria C6, operator >=, threshold 2.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_kelayakan', function (Blueprint $table) {
            $table->id();
            $table->string('minat', 30)->unique();
            $table->string('kriteria', 10)->default('C6');
            $table->string('operator', 5)->default('>=');
            $table->integer('threshold')->default(2);
            $table->string('keterangan', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_kelayakan');
    }
};
