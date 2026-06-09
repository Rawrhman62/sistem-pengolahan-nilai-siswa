<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class DatabaseCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Seed data in order of dependencies
            $this->seedUsers();
            $this->seedAdmins();
            $this->seedTeachers();
            $this->seedLecturers();
            $this->seedClasses();
            $this->seedHomerooms();
            $this->seedStudents();
            $this->seedSubjectsCode();
            $this->seedSubjects();
            $this->seedTeachedSubjects();

            DB::commit();
            $this->command->info('Database seeded successfully from databas.csv!');
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding database: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Seed users table
     */
    private function seedUsers(): void
    {
        $users = [
            [
                'id_user' => 1001,
                'name' => 'Takanashi Hoshino',
                'gender' => 'F',
                'email' => 'hoshino@example.com',
                'phone_number' => '+62 811 1111 1001',
                'password' => Hash::make('Admin123'),
                'profile_picture' => '/public/images/demo/profile_1001.png',
            ],
            [
                'id_user' => 1002,
                'name' => 'Fazri Rahman',
                'gender' => 'M',
                'email' => 'fazrirahman62@gmail.com',
                'phone_number' => '+62 852 6431 2600',
                'password' => Hash::make('Fazri123'),
                'profile_picture' => '/public/images/profile_3312501038.png',
            ],
            [
                'id_user' => 1003,
                'name' => 'Sorasaki Hina',
                'gender' => 'F',
                'email' => 'hina@example.com',
                'phone_number' => '+62 811 1111 1003',
                'password' => Hash::make('Admin123'),
                'profile_picture' => '/public/images/demo/profile_1002.png',
            ],
            [
                'id_user' => 1004,
                'name' => 'Ahmad Rafi Sa\'id F.',
                'gender' => 'M',
                'email' => 'rafi@example.com',
                'phone_number' => '+62 811 1111 1004',
                'password' => Hash::make('Rafi123'),
                'profile_picture' => '/public/images/profile_3312501051.png',
            ],
            [
                'id_user' => 1005,
                'name' => 'Kuchinashi Yume',
                'gender' => 'F',
                'email' => 'yumesenpai47@bmail.com',
                'phone_number' => '+62 867 6767 6767',
                'password' => null,
                'profile_picture' => '/public/images/demo/profile_1003.png',
            ],
            [
                'id_user' => 1006,
                'name' => 'Muradika Laksamana Putri',
                'gender' => 'M',
                'email' => 'muradika@example.com',
                'phone_number' => '+62 811 1111 1006',
                'password' => null,
                'profile_picture' => '/public/images/profile_3312501059.png',
            ],
        ];

        foreach ($users as $user) {
            $this->validateUser($user);
            DB::table('tb_users')->insert($user);
        }

        $this->command->info('✓ Users seeded');
    }

    /**
     * Seed admins table
     */
    private function seedAdmins(): void
    {
        $admins = [
            ['id_user' => 1001],
            ['id_user' => 1004],
        ];

        foreach ($admins as $admin) {
            $this->validateAdmin($admin);
            DB::table('tb_admins')->insert($admin);
        }

        $this->command->info('✓ Admins seeded');
    }

    /**
     * Seed teachers table
     */
    private function seedTeachers(): void
    {
        $teachers = [
            [
                'id_user' => 1003,
                'nomor_induk' => '199510152020012002',
                'date_of_employment' => 2018,
                'teacher_status' => 'employeed',
                'type' => 'pns',
            ],
            [
                'id_user' => 1005,
                'nomor_induk' => '199907250000000198',
                'date_of_employment' => 2020,
                'teacher_status' => 'employeed',
                'type' => 'honorer',
            ],
            [
                'id_user' => 1001,
                'nomor_induk' => '198507252006102002',
                'date_of_employment' => 2017,
                'teacher_status' => 'employeed',
                'type' => 'pns',
            ],
            [
                'id_user' => 1004,
                'nomor_induk' => '197908112000040099',
                'date_of_employment' => 2017,
                'teacher_status' => 'employeed',
                'type' => 'pns',
            ],
        ];

        foreach ($teachers as $teacher) {
            $this->validateTeacher($teacher);
            DB::table('tb_teachers')->insert($teacher);
        }

        $this->command->info('✓ Teachers seeded');
    }

    /**
     * Seed lecturers table
     */
    private function seedLecturers(): void
    {
        $lecturers = [
            ['id_user' => 1003],
            ['id_user' => 1005],
        ];

        foreach ($lecturers as $lecturer) {
            $this->validateLecturer($lecturer);
            DB::table('tb_lecturers')->insert($lecturer);
        }

        $this->command->info('✓ Lecturers seeded');
    }

    /**
     * Seed classes table
     */
    private function seedClasses(): void
    {
        $classes = [
            ['id_class' => 101, 'grade' => 10, 'name' => 'X IPA 1'],
            ['id_class' => 102, 'grade' => 10, 'name' => 'X IPA 2'],
            ['id_class' => 103, 'grade' => 10, 'name' => 'X IPS 1'],
            ['id_class' => 104, 'grade' => 11, 'name' => 'XI IPA 1'],
            ['id_class' => 105, 'grade' => 11, 'name' => 'XI IPA 2'],
            ['id_class' => 106, 'grade' => 11, 'name' => 'XI IPS 1'],
            ['id_class' => 107, 'grade' => 12, 'name' => 'XII IPA 1'],
            ['id_class' => 108, 'grade' => 12, 'name' => 'XII IPA 2'],
            ['id_class' => 109, 'grade' => 12, 'name' => 'XII IPS 1'],
        ];

        foreach ($classes as $class) {
            $this->validateClass($class);
            DB::table('tb_classes')->insert($class);
        }

        $this->command->info('✓ Classes seeded');
    }

    /**
     * Seed homerooms table
     */
    private function seedHomerooms(): void
    {
        $homerooms = [
            ['id_user' => 1003, 'id_class' => 105],
            ['id_user' => 1006, 'id_class' => 109],
        ];

        foreach ($homerooms as $homeroom) {
            $this->validateHomeroom($homeroom);
            DB::table('tb_homerooms')->insert($homeroom);
        }

        $this->command->info('✓ Homerooms seeded');
    }

    /**
     * Seed students table
     */
    private function seedStudents(): void
    {
        $students = [
            [
                'id_user' => 1002,
                'nis' => '240001',
                'nisn' => '3312501038',
                'entry_year' => 2024,
                'id_class' => 105,
            ],
        ];

        foreach ($students as $student) {
            $this->validateStudent($student);
            DB::table('tb_students')->insert($student);
        }

        $this->command->info('✓ Students seeded');
    }

    /**
     * Seed subjects code table
     */
    private function seedSubjectsCode(): void
    {
        $subjectsCodes = [
            ['code' => 'MTKW', 'name' => 'Matematika Wajib'],
            ['code' => 'MTKP', 'name' => 'Matematika Peminatan'],
            ['code' => 'BIND', 'name' => 'Bahasa Indonesia'],
            ['code' => 'BING', 'name' => 'Bahasa Inggris'],
            ['code' => 'FISK', 'name' => 'Fisika'],
            ['code' => 'KMIA', 'name' => 'Kimia'],
            ['code' => 'BIO', 'name' => 'Biologi'],
            ['code' => 'GEO', 'name' => 'Geologi'],
            ['code' => 'SOSI', 'name' => 'Sosiologi'],
            ['code' => 'EKOM', 'name' => 'Ekonomi'],
            ['code' => 'EKOP', 'name' => 'Ekonomi Peminatan'],
            ['code' => 'PAI', 'name' => 'Pendidikan Agama Islam'],
            ['code' => 'PAK', 'name' => 'Pendidikan Agama Kristen'],
            ['code' => 'SENI', 'name' => 'Seni Budaya'],
            ['code' => 'PPKN', 'name' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['code' => 'SJRH', 'name' => 'Sejarah'],
            ['code' => 'PJOK', 'name' => 'Pendidikan Jasmani Olahraga dan Kesehatan'],
        ];

        foreach ($subjectsCodes as $subjectCode) {
            $this->validateSubjectCode($subjectCode);
            DB::table('tb_subjects_code')->insert($subjectCode);
        }

        $this->command->info('✓ Subject codes seeded');
    }

    /**
     * Seed subjects table
     */
    private function seedSubjects(): void
    {
        $subjects = [
            ['id_subjects' => 'S001', 'code' => 'MTKW', 'name' => 'Matematika Wajib Kelas X K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S002', 'code' => 'MTKW', 'name' => 'Matematika Wajib Kelas XI K13', 'grade' => 11, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S003', 'code' => 'MTKW', 'name' => 'Matematika Wajib Kelas XII K13', 'grade' => 12, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S004', 'code' => 'MTKP', 'name' => 'Matematika Peminatan Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S005', 'code' => 'MTKP', 'name' => 'Matematika Peminatan Kelas 11 K13', 'grade' => 11, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S006', 'code' => 'MTKP', 'name' => 'Matematika Peminatan Kelas 12 K13', 'grade' => 12, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S007', 'code' => 'BIND', 'name' => 'Bahasa Indonesia Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S008', 'code' => 'BING', 'name' => 'Bahasa Inggris Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S009', 'code' => 'FISK', 'name' => 'Fisika Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S010', 'code' => 'KMIA', 'name' => 'Kimia Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S011', 'code' => 'BIO', 'name' => 'Biologi Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S012', 'code' => 'GEO', 'name' => 'Geografi Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPS'],
            ['id_subjects' => 'S013', 'code' => 'EKOM', 'name' => 'Ekonomi Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPS'],
            ['id_subjects' => 'S014', 'code' => 'EKOP', 'name' => 'Ekonomi Peminatan Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPA'],
            ['id_subjects' => 'S015', 'code' => 'SOSI', 'name' => 'Sosiologi Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPS'],
            ['id_subjects' => 'S016', 'code' => 'SJRH', 'name' => 'Sejarah Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'IPS'],
            ['id_subjects' => 'S017', 'code' => 'PAI', 'name' => 'Pendidikan Agama Islam Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S018', 'code' => 'PPKN', 'name' => 'Pendidikan Pancasila dan Kewarganegaraan Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S019', 'code' => 'SENI', 'name' => 'Seni Budaya Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S020', 'code' => 'PJOK', 'name' => 'Pendjdikan Jasmani  Olahraga dan Kesehatan Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S021', 'code' => 'PAK', 'name' => 'Pendidikan Agama Kristen Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
        ];

        foreach ($subjects as $subject) {
            $this->validateSubject($subject);
            DB::table('tb_subjects')->insert($subject);
        }

        $this->command->info('✓ Subjects seeded');
    }

    /**
     * Seed teached subjects table
     */
    private function seedTeachedSubjects(): void
    {
        $teachedSubjects = [
            ['id_user' => 1003, 'id_subjects' => 'S001', 'teached_class' => 101],
            ['id_user' => 1003, 'id_subjects' => 'S002', 'teached_class' => 105],
            ['id_user' => 1003, 'id_subjects' => 'S005', 'teached_class' => 105],
        ];

        foreach ($teachedSubjects as $teachedSubject) {
            $this->validateTeachedSubject($teachedSubject);
            DB::table('tb_teached_subjects')->insert($teachedSubject);
        }

        $this->command->info('✓ Teached subjects seeded');
    }

    // Validation methods
    private function validateUser(array $user): void
    {
        if (!isset($user['id_user']) || !is_numeric($user['id_user'])) {
            throw new Exception("Invalid id_user for user");
        }
        if (!isset($user['name']) || empty($user['name'])) {
            throw new Exception("User name is required for id_user: {$user['id_user']}");
        }
        if (!isset($user['email']) || !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email for user: {$user['name']}");
        }
    }

    private function validateAdmin(array $admin): void
    {
        if (!isset($admin['id_user']) || !is_numeric($admin['id_user'])) {
            throw new Exception("Invalid id_user for admin");
        }
        // Verify user exists
        if (!DB::table('tb_users')->where('id_user', $admin['id_user'])->exists()) {
            throw new Exception("User with id_user {$admin['id_user']} does not exist");
        }
    }

    private function validateTeacher(array $teacher): void
    {
        if (!isset($teacher['id_user']) || !is_numeric($teacher['id_user'])) {
            throw new Exception("Invalid id_user for teacher");
        }
        if (!isset($teacher['nomor_induk']) || empty($teacher['nomor_induk'])) {
            throw new Exception("nomor_induk is required for teacher with id_user: {$teacher['id_user']}");
        }
        // Verify user exists
        if (!DB::table('tb_users')->where('id_user', $teacher['id_user'])->exists()) {
            throw new Exception("User with id_user {$teacher['id_user']} does not exist");
        }
    }

    private function validateLecturer(array $lecturer): void
    {
        if (!isset($lecturer['id_user']) || !is_numeric($lecturer['id_user'])) {
            throw new Exception("Invalid id_user for lecturer");
        }
        // Verify user exists
        if (!DB::table('tb_users')->where('id_user', $lecturer['id_user'])->exists()) {
            throw new Exception("User with id_user {$lecturer['id_user']} does not exist");
        }
    }

    private function validateClass(array $class): void
    {
        if (!isset($class['id_class']) || !is_numeric($class['id_class'])) {
            throw new Exception("Invalid id_class for class");
        }
        if (!isset($class['name']) || empty($class['name'])) {
            throw new Exception("Class name is required for id_class: {$class['id_class']}");
        }
    }

    private function validateHomeroom(array $homeroom): void
    {
        if (!isset($homeroom['id_user']) || !is_numeric($homeroom['id_user'])) {
            throw new Exception("Invalid id_user for homeroom");
        }
        // Verify user exists
        if (!DB::table('tb_users')->where('id_user', $homeroom['id_user'])->exists()) {
            throw new Exception("User with id_user {$homeroom['id_user']} does not exist");
        }
        // Verify class exists if id_class is set
        if (isset($homeroom['id_class']) && !DB::table('tb_classes')->where('id_class', $homeroom['id_class'])->exists()) {
            throw new Exception("Class with id_class {$homeroom['id_class']} does not exist");
        }
    }

    private function validateStudent(array $student): void
    {
        if (!isset($student['id_user']) || !is_numeric($student['id_user'])) {
            throw new Exception("Invalid id_user for student");
        }
        if (!isset($student['nis']) || empty($student['nis'])) {
            throw new Exception("nis is required for student with id_user: {$student['id_user']}");
        }
        // Verify user exists
        if (!DB::table('tb_users')->where('id_user', $student['id_user'])->exists()) {
            throw new Exception("User with id_user {$student['id_user']} does not exist");
        }
    }

    private function validateSubjectCode(array $subjectCode): void
    {
        if (!isset($subjectCode['code']) || empty($subjectCode['code'])) {
            throw new Exception("Subject code is required");
        }
        if (!isset($subjectCode['name']) || empty($subjectCode['name'])) {
            throw new Exception("Subject code name is required for code: {$subjectCode['code']}");
        }
    }

    private function validateSubject(array $subject): void
    {
        if (!isset($subject['id_subjects']) || empty($subject['id_subjects'])) {
            throw new Exception("id_subjects is required for subject");
        }
        if (!isset($subject['name']) || empty($subject['name'])) {
            throw new Exception("Subject name is required for id_subjects: {$subject['id_subjects']}");
        }
    }

    private function validateTeachedSubject(array $teachedSubject): void
    {
        if (!isset($teachedSubject['id_user']) || !is_numeric($teachedSubject['id_user'])) {
            throw new Exception("Invalid id_user for teached subject");
        }
        if (!isset($teachedSubject['id_subjects']) || empty($teachedSubject['id_subjects'])) {
            throw new Exception("id_subjects is required for teached subject");
        }
        // Verify user exists
        if (!DB::table('tb_users')->where('id_user', $teachedSubject['id_user'])->exists()) {
            throw new Exception("User with id_user {$teachedSubject['id_user']} does not exist");
        }
        // Verify subject exists
        if (!DB::table('tb_subjects')->where('id_subjects', $teachedSubject['id_subjects'])->exists()) {
            throw new Exception("Subject with id_subjects {$teachedSubject['id_subjects']} does not exist");
        }
    }
}
