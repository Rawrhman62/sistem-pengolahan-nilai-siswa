<?php

$file = 'app/Http/Controllers/AdminController.php';
$content = file_get_contents($file);

// Replace imports
$content = str_replace('use App\Models\Siswa;', 'use App\Models\Student;', $content);
$content = str_replace('use App\Models\Guru;', 'use App\Models\Teacher;', $content);
$content = str_replace('use App\Models\Mapel;', 'use App\Models\Subject;', $content);
$content = str_replace('use App\Models\Kelas;', 'use App\Models\ClassRoom;', $content);

// Replace Model calls
$content = str_replace('Siswa::', 'Student::', $content);
$content = str_replace('Guru::', 'Teacher::', $content);
$content = str_replace('Mapel::', 'Subject::', $content);
$content = str_replace('Kelas::', 'ClassRoom::', $content);

// Fix queries and relations
$content = preg_replace('/siswas\.user_id/', 'tb_students.id_user', $content);
$content = preg_replace('/gurus\.user_id/', 'tb_teachers.id_user', $content);
$content = preg_replace('/kelas\.wali_kelas_id/', 'tb_classes.wali_kelas_id', $content);

// Fix users table references
$content = preg_replace('/users\.id/', 'tb_users.id_user', $content);
$content = preg_replace('/users\.name/', 'tb_users.name', $content);

// Update search query fields
$content = str_replace("orWhere('user_name'", "orWhere('id_user'", $content);
$content = str_replace("where('user_id'", "where('id_user'", $content);

// Fix User register logic
$register_old = <<<EOT
        User::create([
            'name' => \$validated['name'],
            'user_name' => \$validated['user_name'],
            'user_id' => \$validated['user_id'],
            'email' => \$validated['email'],
            'phone_number' => \$validated['phone_number'],
            'role' => \$role,
            'password_set' => false,
            'password' => null,
        ]);
EOT;

$register_new = <<<EOT
        \$user = User::create([
            'name' => \$validated['name'],
            'id_user' => \$validated['user_id'],
            'email' => \$validated['email'],
            'phone_number' => \$validated['phone_number'],
            'password' => null,
        ]);

        if (str_contains(\$role, 'administrator')) {
            \App\Models\Admin::create(['id_user' => \$user->id_user]);
        }
        if (str_contains(\$role, 'lectureTeacher')) {
            \App\Models\Lecturer::create(['id_user' => \$user->id_user]);
            \App\Models\Teacher::create(['id_user' => \$user->id_user]);
        }
        if (str_contains(\$role, 'homeroomTeacher')) {
            \App\Models\Homeroom::create(['id_user' => \$user->id_user]);
            if (!str_contains(\$role, 'lectureTeacher')) {
                \App\Models\Teacher::create(['id_user' => \$user->id_user]);
            }
        }
        if (str_contains(\$role, 'student')) {
            \App\Models\Student::create(['id_user' => \$user->id_user]);
        }
EOT;

$content = str_replace($register_old, $register_new, $content);

// Fix imports logic for students
$import_student_old = <<<EOT
                // Create user account for student
                \$user = User::create([
                    'name' => \$siswaData['nama'],
                    'user_name' => 'student_' . \$siswaData['nis'],
                    'user_id' => \$siswaData['nis'],
                    'email' => 'student' . \$siswaData['nis'] . '@school.id',
                    'phone_number' => null,
                    'role' => 'student',
                    'password_set' => false,
                    'password' => null,
                ]);

                // Create siswa record linked to user
                Student::create([
                    'user_id' => \$user->id,
                    'nis' => \$siswaData['nis'],
                    'kelas' => \$siswaData['kelas'],
                ]);
EOT;

$import_student_new = <<<EOT
                \$user = User::create([
                    'name' => \$siswaData['nama'],
                    'id_user' => \$siswaData['nis'],
                    'email' => 'student' . \$siswaData['nis'] . '@school.id',
                    'phone_number' => null,
                    'password' => null,
                ]);

                \App\Models\Student::create([
                    'id_user' => \$user->id_user,
                    'nis' => \$siswaData['nis'],
                ]);
EOT;

$content = str_replace($import_student_old, $import_student_new, $content);

// Fix imports logic for teachers
$import_teacher_old = <<<EOT
                // Create user account for teacher
                \$user = User::create([
                    'name' => \$guruData['nama'],
                    'user_name' => 'teacher_' . \$guruData['nip'],
                    'user_id' => \$guruData['nip'],
                    'email' => 'teacher' . str_replace(' ', '', \$guruData['nip']) . '@school.id',
                    'phone_number' => null,
                    'role' => 'lectureTeacher',
                    'password_set' => false,
                    'password' => null,
                ]);

                // Create guru record linked to user
                Teacher::create([
                    'user_id' => \$user->id,
                    'nip' => \$guruData['nip'],
                ]);
EOT;

$import_teacher_new = <<<EOT
                \$user = User::create([
                    'name' => \$guruData['nama'],
                    'id_user' => \$guruData['nip'],
                    'email' => 'teacher' . str_replace(' ', '', \$guruData['nip']) . '@school.id',
                    'phone_number' => null,
                    'password' => null,
                ]);

                \App\Models\Lecturer::create(['id_user' => \$user->id_user]);
                Teacher::create([
                    'id_user' => \$user->id_user,
                    'nomor_induk' => \$guruData['nip'],
                ]);
EOT;

$content = str_replace($import_teacher_old, $import_teacher_new, $content);

file_put_contents($file, $content);
echo "Done";
