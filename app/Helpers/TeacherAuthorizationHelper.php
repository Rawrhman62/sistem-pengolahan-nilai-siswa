<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Collection;

/**
 * Helper class for teacher authorization checks
 * 
 * This class provides reusable methods to check if teachers have permission
 * to access specific data based on their role and class assignments.
 */
class TeacherAuthorizationHelper
{
    /**
     * Get all classes a teacher is authorized to access
     * 
     * For lecture teachers: classes they teach
     * For homeroom teachers: their assigned homeroom class
     * For dual role teachers: combination of both
     *
     * @param User $teacher
     * @return array Array of class names/IDs
     */
    public static function getAuthorizedClasses(User $teacher): array
    {
        $classes = [];

        // If homeroom teacher, add their homeroom class
        if ($teacher->hasRole('homeroomTeacher')) {
            $homeroomClass = self::getHomeroomClass($teacher);
            if ($homeroomClass) {
                $classes[] = $homeroomClass;
            }
        }

        // If lecture teacher, add classes they teach
        if ($teacher->hasRole('lectureTeacher')) {
            $lectureClasses = self::getLectureClasses($teacher);
            $classes = array_merge($classes, $lectureClasses);
        }

        // Remove duplicates and return
        return array_unique($classes);
    }

    /**
     * Get the homeroom class assigned to a homeroom teacher
     * 
     * Note: This is a placeholder implementation. In a real application,
     * you would query a teacher_assignments or similar table to get the
     * actual homeroom class assignment.
     *
     * @param User $teacher
     * @return string|null The class name or null if not assigned
     */
    public static function getHomeroomClass(User $teacher): ?string
    {
        if (!$teacher->hasRole('homeroomTeacher')) {
            return null;
        }

        // TODO: Replace with actual database query
        // Example: return TeacherAssignment::where('teacher_id', $teacher->id)
        //              ->where('assignment_type', 'homeroom')
        //              ->value('class_name');
        
        // Placeholder: Return null for now
        // When implementing, query the teacher_assignments table
        return null;
    }

    /**
     * Get all classes a lecture teacher teaches
     * 
     * Note: This is a placeholder implementation. In a real application,
     * you would query a teacher_assignments or class_schedules table.
     *
     * @param User $teacher
     * @return array Array of class names
     */
    public static function getLectureClasses(User $teacher): array
    {
        if (!$teacher->hasRole('lectureTeacher')) {
            return [];
        }

        // TODO: Replace with actual database query
        // Example: return TeacherAssignment::where('teacher_id', $teacher->id)
        //              ->where('assignment_type', 'lecture')
        //              ->pluck('class_name')
        //              ->toArray();
        
        // Placeholder: Return empty array for now
        // When implementing, query the teacher_assignments or schedules table
        return [];
    }

    /**
     * Get all subjects a teacher is authorized to teach
     *
     * @param User $teacher
     * @return array Array of subject codes
     */
    public static function getAuthorizedSubjects(User $teacher): array
    {
        if (!$teacher->hasRole('lectureTeacher')) {
            return [];
        }

        // TODO: Replace with actual database query
        // Example: return TeacherAssignment::where('teacher_id', $teacher->id)
        //              ->pluck('subject_code')
        //              ->toArray();
        
        // Placeholder: Return empty array for now
        return [];
    }

    /**
     * Check if a teacher can access a specific class
     *
     * @param User $teacher
     * @param string $className
     * @return bool
     */
    public static function canAccessClass(User $teacher, string $className): bool
    {
        $authorizedClasses = self::getAuthorizedClasses($teacher);
        return in_array($className, $authorizedClasses);
    }

    /**
     * Check if a teacher can access a specific subject
     *
     * @param User $teacher
     * @param string $subjectCode
     * @return bool
     */
    public static function canAccessSubject(User $teacher, string $subjectCode): bool
    {
        // Homeroom teachers can access all subjects for their class
        if ($teacher->hasRole('homeroomTeacher')) {
            return true;
        }

        // Lecture teachers can only access their assigned subjects
        $authorizedSubjects = self::getAuthorizedSubjects($teacher);
        return in_array($subjectCode, $authorizedSubjects);
    }

    /**
     * Check if a teacher can manage a specific student
     * 
     * A teacher can manage a student if the student is in one of their authorized classes
     *
     * @param User $teacher
     * @param int $studentId
     * @return bool
     */
    public static function canManageStudent(User $teacher, int $studentId): bool
    {
        $student = Siswa::find($studentId);
        
        if (!$student) {
            return false;
        }

        return self::canAccessClass($teacher, $student->kelas);
    }

    /**
     * Check if a teacher can export data for a specific class
     *
     * @param User $teacher
     * @param string $className
     * @return bool
     */
    public static function canExportClass(User $teacher, string $className): bool
    {
        return self::canAccessClass($teacher, $className);
    }

    /**
     * Check if a teacher can import data for a specific class
     *
     * @param User $teacher
     * @param string $className
     * @return bool
     */
    public static function canImportClass(User $teacher, string $className): bool
    {
        return self::canAccessClass($teacher, $className);
    }

    /**
     * Filter a collection of students to only include those the teacher can access
     *
     * @param User $teacher
     * @param Collection $students
     * @return Collection
     */
    public static function filterAuthorizedStudents(User $teacher, Collection $students): Collection
    {
        $authorizedClasses = self::getAuthorizedClasses($teacher);
        
        return $students->filter(function ($student) use ($authorizedClasses) {
            return in_array($student->kelas, $authorizedClasses);
        });
    }

    /**
     * Validate that all students in a collection belong to authorized classes
     * 
     * Returns an array of unauthorized class names if any are found
     *
     * @param User $teacher
     * @param Collection $students
     * @return array Array of unauthorized class names (empty if all authorized)
     */
    public static function validateStudentAuthorization(User $teacher, Collection $students): array
    {
        $authorizedClasses = self::getAuthorizedClasses($teacher);
        $unauthorizedClasses = [];

        foreach ($students as $student) {
            $className = is_array($student) ? $student['kelas'] : $student->kelas;
            
            if (!in_array($className, $authorizedClasses)) {
                $unauthorizedClasses[] = $className;
            }
        }

        return array_unique($unauthorizedClasses);
    }

    /**
     * Check if a teacher has any class assignments
     *
     * @param User $teacher
     * @return bool
     */
    public static function hasClassAssignments(User $teacher): bool
    {
        return !empty(self::getAuthorizedClasses($teacher));
    }

    /**
     * Get a query builder for students that a teacher can access
     * 
     * This can be used to build more complex queries with authorization built in
     *
     * @param User $teacher
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getAuthorizedStudentsQuery(User $teacher)
    {
        $authorizedClasses = self::getAuthorizedClasses($teacher);
        
        return Siswa::whereIn('kelas', $authorizedClasses);
    }

    /**
     * Check if a teacher is authorized to perform bulk operations
     * 
     * Bulk operations (like import/export) require at least one class assignment
     *
     * @param User $teacher
     * @return bool
     */
    public static function canPerformBulkOperations(User $teacher): bool
    {
        return self::hasClassAssignments($teacher);
    }

    /**
     * Get authorization summary for a teacher
     * 
     * Returns an array with authorization details for debugging/display
     *
     * @param User $teacher
     * @return array
     */
    public static function getAuthorizationSummary(User $teacher): array
    {
        return [
            'is_teacher' => $teacher->isTeacher(),
            'is_homeroom_teacher' => $teacher->hasRole('homeroomTeacher'),
            'is_lecture_teacher' => $teacher->hasRole('lectureTeacher'),
            'homeroom_class' => self::getHomeroomClass($teacher),
            'lecture_classes' => self::getLectureClasses($teacher),
            'authorized_classes' => self::getAuthorizedClasses($teacher),
            'authorized_subjects' => self::getAuthorizedSubjects($teacher),
            'can_perform_bulk_operations' => self::canPerformBulkOperations($teacher),
        ];
    }
}
