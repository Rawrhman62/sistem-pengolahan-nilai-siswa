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
        Schema::create('tb_nilai_harian', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_nilai_harian')->unique();
            for ($i = 1; $i <= 12; $i++) {
                $table->string("minggu_$i")->nullable();
            }
            $table->timestamps();
        });

        Schema::create('tb_nilai_ulangan', function (Blueprint $table) {
            $table->id();
            $table->string('id_nilai_ulangan')->unique();
            for ($i = 1; $i <= 8; $i++) {
                $table->string("ulangan_$i")->nullable();
            }
            $table->timestamps();
        });

        Schema::create('tb_nilai_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('id_nilai_ujian')->unique();
            $table->string('awal_ganjil')->nullable();
            $table->string('akhir_ganjil')->nullable();
            $table->string('awal_genap')->nullable();
            $table->string('akhir_genap')->nullable();
            $table->string('year')->nullable();
            $table->timestamps();
        });

        Schema::create('tb_nilai_keterampilan', function (Blueprint $table) {
            $table->id();
            $table->string('id_nilai_keterampilan')->unique();
            $table->string('nilai')->nullable();
            $table->timestamps();
        });

        Schema::create('tb_nilai', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user');
            $table->string('id_nilai')->unique();
            $table->bigInteger('id_nilai_harian')->nullable();
            $table->string('id_nilai_keterampilan')->nullable();
            $table->string('id_nilai_ulangan')->nullable();
            $table->string('id_nilai_ujian')->nullable();
            $table->string('id_subjects')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_nilai_harian')->references('id_nilai_harian')->on('tb_nilai_harian')->onDelete('set null');
            $table->foreign('id_nilai_keterampilan')->references('id_nilai_keterampilan')->on('tb_nilai_keterampilan')->onDelete('set null');
            $table->foreign('id_nilai_ulangan')->references('id_nilai_ulangan')->on('tb_nilai_ulangan')->onDelete('set null');
            $table->foreign('id_nilai_ujian')->references('id_nilai_ujian')->on('tb_nilai_ujian')->onDelete('set null');
            $table->foreign('id_subjects')->references('id_subjects')->on('tb_subjects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_nilai');
        Schema::dropIfExists('tb_nilai_keterampilan');
        Schema::dropIfExists('tb_nilai_ujian');
        Schema::dropIfExists('tb_nilai_ulangan');
        Schema::dropIfExists('tb_nilai_harian');
    }
};
