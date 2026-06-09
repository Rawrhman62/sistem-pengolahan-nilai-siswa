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
        Schema::create('tb_admins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unique();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
        });

        Schema::create('tb_teachers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unique();
            $table->string('nomor_induk')->unique();
            $table->integer('date_of_employment')->nullable();
            $table->string('teacher_status')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->index('nomor_induk');
        });

        Schema::create('tb_lecturers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unique();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
        });

        // We will add the foreign key for id_class to tb_homerooms later if needed, 
        // because tb_classes is created in a later migration. For now, just create the column.
        Schema::create('tb_homerooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unique();
            $table->bigInteger('id_class')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            // $table->foreign('id_class')->references('id_class')->on('tb_classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_homerooms');
        Schema::dropIfExists('tb_lecturers');
        Schema::dropIfExists('tb_teachers');
        Schema::dropIfExists('tb_admins');
    }
};
