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
        // Add tahun_ajaran to siswas table
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('tahun_ajaran', 20)->nullable()->after('kelas');
            $table->index('tahun_ajaran');
        });

        // Remove tahun_ajaran and kelas from nilai table
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn(['tahun_ajaran', 'kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back tahun_ajaran and kelas to nilai table
        Schema::table('nilai', function (Blueprint $table) {
            $table->string('tahun_ajaran', 20)->after('predikat');
            $table->string('kelas', 10)->nullable()->after('tahun_ajaran');
        });

        // Remove tahun_ajaran from siswas table
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran');
        });
    }
};
