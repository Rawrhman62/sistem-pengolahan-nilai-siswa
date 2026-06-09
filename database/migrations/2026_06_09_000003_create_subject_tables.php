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
        Schema::create('tb_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('id_subjects')->unique();
            $table->string('code')->nullable();
            $table->string('name');
            $table->integer('grade')->nullable();
            $table->string('curriculum')->nullable();
            $table->string('group')->nullable();
            $table->timestamps();
        });

        Schema::create('tb_subjects_code', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tb_teached_subjects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user');
            $table->string('id_subjects');
            $table->bigInteger('teached_class')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_subjects')->references('id_subjects')->on('tb_subjects')->onDelete('cascade');
            $table->foreign('teached_class')->references('id_class')->on('tb_classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_teached_subjects');
        Schema::dropIfExists('tb_subjects_code');
        Schema::dropIfExists('tb_subjects');
    }
};
