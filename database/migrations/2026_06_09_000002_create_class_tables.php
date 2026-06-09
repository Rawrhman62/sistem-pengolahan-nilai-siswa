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
        Schema::create('tb_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_class')->unique();
            $table->integer('grade')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tb_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unique();
            $table->string('nis')->unique();
            $table->string('nisn')->nullable();
            $table->integer('entry_year')->nullable();
            $table->bigInteger('id_class')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_class')->references('id_class')->on('tb_classes')->onDelete('set null');
            $table->index('nis');
        });
        
        // Add foreign key constraint to tb_homerooms
        Schema::table('tb_homerooms', function (Blueprint $table) {
            $table->foreign('id_class')->references('id_class')->on('tb_classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_homerooms', function (Blueprint $table) {
            $table->dropForeign(['id_class']);
        });
        
        Schema::dropIfExists('tb_students');
        Schema::dropIfExists('tb_classes');
    }
};
