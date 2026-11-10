<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema tabel ambang batas kelayakan (filter_threshold) skor C6 per minat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filter_threshold', function (Blueprint $table) {
            $table->id();
            $table->string('minat', 30)->unique();
            $table->string('kriteria', 10)->default('C6');
            $table->string('operator', 5)->default('>=');
            $table->integer('threshold')->default(1);
            $table->string('keterangan', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filter_threshold');
    }
};
