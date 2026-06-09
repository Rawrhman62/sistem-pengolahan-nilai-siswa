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
        Schema::create('tb_kkm', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->string('kkm');
            $table->timestamps();
        });

        Schema::create('tb_rasio_bobot_nilai', function (Blueprint $table) {
            $table->id();
            $table->string('nilai_harian');
            $table->string('nilai_keterampilan');
            $table->string('nilai_ulangan');
            $table->string('nilai_ujian');
            $table->string('void')->default('0');
            $table->timestamps();
        });

        Schema::create('tb_predikat', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->string('a_range')->nullable(); // Changed from 'A' to 'a_range' for DB compatibility
            $table->string('b_range')->nullable(); // Changed from 'B'
            $table->string('c_range')->nullable(); // Changed from 'C'
            $table->string('d_range')->nullable(); // Changed from 'D'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_predikat');
        Schema::dropIfExists('tb_rasio_bobot_nilai');
        Schema::dropIfExists('tb_kkm');
    }
};
