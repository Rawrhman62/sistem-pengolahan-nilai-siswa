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
        Schema::table('gurus', function (Blueprint $table) {
            // Add foreign key to users table
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            
            // Remove nama field (will use users.name instead)
            $table->dropColumn('nama');
            
            // Remove mapel field (will be handled through relationships)
            $table->dropColumn('mapel');
            
            // Keep nip as it's teacher-specific
            
            // Add teacher-specific fields
            $table->date('tanggal_lahir')->nullable()->after('nip');
            $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tempat_lahir');
            $table->text('alamat')->nullable()->after('jenis_kelamin');
            $table->string('pendidikan_terakhir')->nullable()->after('alamat');
            $table->string('gelar')->nullable()->after('pendidikan_terakhir');
            $table->date('tanggal_bergabung')->nullable()->after('gelar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn([
                'tanggal_lahir',
                'tempat_lahir',
                'jenis_kelamin',
                'alamat',
                'pendidikan_terakhir',
                'gelar',
                'tanggal_bergabung'
            ]);
            
            // Add back nama and mapel fields
            $table->string('nama')->after('id');
            $table->string('mapel')->after('nip');
            
            // Drop foreign key
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
