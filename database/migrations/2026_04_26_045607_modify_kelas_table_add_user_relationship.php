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
        Schema::table('kelas', function (Blueprint $table) {
            // Add foreign key to users table for wali_kelas (homeroom teacher)
            $table->foreignId('wali_kelas_id')->nullable()->after('tingkat')->constrained('users')->onDelete('set null');
            
            // Keep wali_kelas as string for now (for backward compatibility)
            // We'll use wali_kelas_id as the primary reference
            
            // Add class-specific fields
            $table->integer('kapasitas')->default(40)->after('wali_kelas_id');
            $table->integer('jumlah_siswa')->default(0)->after('kapasitas');
            $table->string('ruangan')->nullable()->after('jumlah_siswa');
            $table->string('tahun_ajaran')->nullable()->after('ruangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn([
                'kapasitas',
                'jumlah_siswa',
                'ruangan',
                'tahun_ajaran'
            ]);
            
            // Drop foreign key
            $table->dropForeign(['wali_kelas_id']);
            $table->dropColumn('wali_kelas_id');
        });
    }
};
