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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // Student user_id
            $table->string('mapel'); // Subject name
            $table->integer('nilai_pengetahuan')->nullable(); // Knowledge score
            $table->integer('nilai_keterampilan')->nullable(); // Skills score
            $table->decimal('nilai_akhir', 5, 2)->nullable(); // Final score
            $table->string('predikat', 1)->nullable(); // Grade (A, B, C, D)
            $table->string('semester', 20); // Semester (e.g., "Ganjil (1)", "Genap (2)")
            $table->string('tahun_ajaran', 20); // Academic year (e.g., "2025/2026")
            $table->string('kelas', 10)->nullable(); // Class (e.g., "X-A")
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['user_id', 'semester', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
