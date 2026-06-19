<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_tanggal_tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 10); // e.g., "2025/2026"
            $table->date('tanggal_mulai'); // Start date of academic year
            $table->date('tanggal_selesai'); // End date of academic year
            $table->timestamps();
        });

        // Insert default current academic year (July 2025 - June 2026)
        DB::table('tb_tanggal_tahun_ajaran')->insert([
            'tahun_ajaran' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tanggal_tahun_ajaran');
    }
};
