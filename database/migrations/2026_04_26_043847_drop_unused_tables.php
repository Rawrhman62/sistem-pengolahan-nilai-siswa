<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Drop duplicate/unused tables
        Schema::dropIfExists('teacher_subjects'); // Not used in current implementation
        Schema::dropIfExists('attendance');       // Not used in current implementation
        Schema::dropIfExists('grades');           // Not used in current implementation
        Schema::dropIfExists('students');         // Duplicate of 'siswas'
        Schema::dropIfExists('subjects');         // Duplicate of 'mapels'
        Schema::dropIfExists('classes');          // Duplicate of 'kelas'
        Schema::dropIfExists('academic_years');   // Not used in current implementation

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables if needed (basic structure only)
        
        Schema::create('classes', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('grade_level');
            $table->timestamps();
        });

        Schema::create('students', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('student_id')->unique();
            $table->timestamps();
        });

        Schema::create('subjects', function ($table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('teacher_subjects', function ($table) {
            $table->id();
            $table->foreignId('teacher_id');
            $table->foreignId('subject_id');
            $table->timestamps();
        });

        Schema::create('attendance', function ($table) {
            $table->id();
            $table->foreignId('student_id');
            $table->date('date');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('grades', function ($table) {
            $table->id();
            $table->foreignId('student_id');
            $table->foreignId('subject_id');
            $table->decimal('grade', 5, 2);
            $table->timestamps();
        });

        Schema::create('academic_years', function ($table) {
            $table->id();
            $table->string('year');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }
};
