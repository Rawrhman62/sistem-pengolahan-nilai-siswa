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
        Schema::table('siswas', function (Blueprint $table) {
            // Add foreign key to users table
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            
            // Remove nama field (will use users.name instead)
            $table->dropColumn('nama');
            
            // Keep nis as it's student-specific
            // Keep kelas as it's student-specific
            
            // Add student-specific fields
            $table->string('nisn')->nullable()->after('nis'); // National Student ID
            $table->date('tanggal_lahir')->nullable()->after('nisn');
            $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tempat_lahir');
            $table->text('alamat')->nullable()->after('jenis_kelamin');
            $table->string('nama_wali')->nullable()->after('alamat');
            $table->string('telepon_wali')->nullable()->after('nama_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn([
                'nisn',
                'tanggal_lahir',
                'tempat_lahir',
                'jenis_kelamin',
                'alamat',
                'nama_wali',
                'telepon_wali'
            ]);
            
            // Add back nama field
            $table->string('nama')->after('id');
            
            // Drop foreign key
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
