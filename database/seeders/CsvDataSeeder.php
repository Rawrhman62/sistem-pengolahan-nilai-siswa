<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CsvDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Import or update classes
        $classes = [
            ['id_class' => 101, 'name' => 'X IPA 1', 'grade' => 10],
            ['id_class' => 102, 'name' => 'X IPA 2', 'grade' => 10],
            ['id_class' => 103, 'name' => 'X IPS 1', 'grade' => 10],
            ['id_class' => 104, 'name' => 'XI IPA 1', 'grade' => 11],
            ['id_class' => 105, 'name' => 'XI IPA 2', 'grade' => 11],
            ['id_class' => 106, 'name' => 'XI IPS 1', 'grade' => 11],
            ['id_class' => 107, 'name' => 'XII IPA 1', 'grade' => 12],
            ['id_class' => 108, 'name' => 'XII IPA 2', 'grade' => 12],
            ['id_class' => 109, 'name' => 'XII IPS 1', 'grade' => 12],
        ];

        foreach ($classes as $class) {
            DB::table('tb_classes')->updateOrInsert(
                ['id_class' => $class['id_class']],
                array_merge($class, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        echo "Classes imported\n";

        // Import subjects
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
            ['id_subjects' => 'S020', 'code' => 'PJOK', 'name' => 'Pendidikan Jasmani Olahraga dan Kesehatan Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
            ['id_subjects' => 'S021', 'code' => 'PAK', 'name' => 'Pendidikan Agama Kristen Kelas 10 K13', 'grade' => 10, 'curriculum' => 'Kurikulum 2013', 'group' => 'Wajib'],
        ];

        foreach ($subjects as $subject) {
            DB::table('tb_subjects')->updateOrInsert(
                ['id_subjects' => $subject['id_subjects']],
                array_merge($subject, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        echo "Subjects imported\n";

        // Import teached subjects
        $teachedSubjects = [
            ['id_user' => 1003, 'id_subjects' => 'S001', 'teached_class' => 101],
            ['id_user' => 1003, 'id_subjects' => 'S002', 'teached_class' => 105],
            ['id_user' => 1003, 'id_subjects' => 'S005', 'teached_class' => 105],
        ];

        foreach ($teachedSubjects as $ts) {
            DB::table('tb_teached_subjects')->updateOrInsert(
                ['id_user' => $ts['id_user'], 'id_subjects' => $ts['id_subjects'], 'teached_class' => $ts['teached_class']],
                array_merge($ts, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        echo "Teached subjects imported\n";

        // Import subject codes
        $subjectCodes = [
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

        foreach ($subjectCodes as $sc) {
            DB::table('tb_subjects_code')->updateOrInsert(
                ['code' => $sc['code']],
                array_merge($sc, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        echo "Subject codes imported\n";

        // Import KKM data
        DB::table('tb_kkm')->updateOrInsert(['grade' => 10], ['grade' => 10, 'kkm' => 78, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('tb_kkm')->updateOrInsert(['grade' => 11], ['grade' => 11, 'kkm' => 80, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('tb_kkm')->updateOrInsert(['grade' => 12], ['grade' => 12, 'kkm' => 82, 'created_at' => now(), 'updated_at' => now()]);

        echo "KKM data imported\n";

        // Import rasio bobot nilai
        DB::table('tb_rasio_bobot_nilai')->updateOrInsert(
            ['id' => 1],
            ['nilai_harian' => 25, 'nilai_keterampilan' => 25, 'nilai_ulangan' => 25, 'nilai_ujian' => 25, 'void' => 0, 'created_at' => now(), 'updated_at' => now()]
        );

        echo "Rasio bobot nilai imported\n";

        // Import predikat data
        $predikatData = [
            ['grade' => 10, 'a_range' => '100-93', 'b_range' => '92-83', 'c_range' => '83-78', 'd_range' => '>78'],
            ['grade' => 11, 'a_range' => '100-93', 'b_range' => '92-83', 'c_range' => '83-80', 'd_range' => '>80'],
            ['grade' => 12, 'a_range' => '100-93', 'b_range' => '92-83', 'c_range' => '83-82', 'd_range' => '>82'],
        ];

        foreach ($predikatData as $pd) {
            DB::table('tb_predikat')->updateOrInsert(
                ['grade' => $pd['grade']],
                array_merge($pd, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        echo "Predikat data imported\n";

        // Import sample nilai data
        DB::table('tb_nilai_harian')->updateOrInsert(
            ['id_nilai_harian' => 1000000001],
            ['id_nilai_harian' => 1000000001, 'minggu_1' => 82, 'minggu_2' => 90, 'minggu_3' => 84, 'minggu_4' => 83, 'minggu_5' => 76, 'minggu_6' => 78, 'minggu_7' => 77, 'minggu_8' => 79, 'minggu_9' => 95, 'minggu_10' => 100, 'minggu_11' => 88, 'minggu_12' => 95, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai_harian')->updateOrInsert(
            ['id_nilai_harian' => 1000000002],
            ['id_nilai_harian' => 1000000002, 'minggu_1' => 100, 'minggu_2' => 100, 'minggu_3' => null, 'minggu_4' => null, 'minggu_5' => 80, 'minggu_6' => 90, 'minggu_7' => 82, 'minggu_8' => 88, 'minggu_9' => 84, 'minggu_10' => 86, 'minggu_11' => 85, 'minggu_12' => 99, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai_harian')->updateOrInsert(
            ['id_nilai_harian' => 1000000003],
            ['id_nilai_harian' => 1000000003, 'minggu_1' => 100, 'minggu_2' => 100, 'minggu_3' => 100, 'minggu_4' => 100, 'minggu_5' => 100, 'minggu_6' => 100, 'minggu_7' => null, 'minggu_8' => 100, 'minggu_9' => 100, 'minggu_10' => 100, 'minggu_11' => 100, 'minggu_12' => 100, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai_harian')->updateOrInsert(
            ['id_nilai_harian' => 1000000004],
            ['id_nilai_harian' => 1000000004, 'minggu_1' => null, 'minggu_2' => 83, 'minggu_3' => 84, 'minggu_4' => 92, 'minggu_5' => null, 'minggu_6' => 93, 'minggu_7' => 94, 'minggu_8' => 95, 'minggu_9' => 96, 'minggu_10' => 97, 'minggu_11' => 98, 'minggu_12' => 99, 'created_at' => now(), 'updated_at' => now()]
        );

        echo "Nilai harian imported\n";

        // Import nilai ulangan
        DB::table('tb_nilai_ulangan')->updateOrInsert(
            ['id_nilai_ulangan' => 1000001],
            ['id_nilai_ulangan' => 1000001, 'ulangan_1' => 79, 'ulangan_2' => 81, 'ulangan_3' => 88, 'ulangan_4' => 84, 'ulangan_5' => 85, 'ulangan_6' => 90, 'ulangan_7' => null, 'ulangan_8' => null, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai_ulangan')->updateOrInsert(
            ['id_nilai_ulangan' => 1000002],
            ['id_nilai_ulangan' => 1000002, 'ulangan_1' => 83, 'ulangan_2' => 87, 'ulangan_3' => 100, 'ulangan_4' => null, 'ulangan_5' => null, 'ulangan_6' => null, 'ulangan_7' => null, 'ulangan_8' => null, 'created_at' => now(), 'updated_at' => now()]
        );

        echo "Nilai ulangan imported\n";

        // Import nilai ujian
        DB::table('tb_nilai_ujian')->updateOrInsert(
            ['id_nilai_ujian' => 1000001],
            ['id_nilai_ujian' => 1000001, 'awal_ganjil' => 82, 'akhir_ganjil' => 83, 'awal_genap' => 84, 'akhir_genap' => 85, 'year' => 2024, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai_ujian')->updateOrInsert(
            ['id_nilai_ujian' => 1000002],
            ['id_nilai_ujian' => 1000002, 'awal_ganjil' => 86, 'akhir_ganjil' => 87, 'awal_genap' => null, 'akhir_genap' => null, 'year' => 2025, 'created_at' => now(), 'updated_at' => now()]
        );

        echo "Nilai ujian imported\n";

        // Import nilai keterampilan
        DB::table('tb_nilai_keterampilan')->updateOrInsert(['id_nilai_keterampilan' => 1000001], ['id_nilai_keterampilan' => 1000001, 'nilai' => 84, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('tb_nilai_keterampilan')->updateOrInsert(['id_nilai_keterampilan' => 1000002], ['id_nilai_keterampilan' => 1000002, 'nilai' => 86, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('tb_nilai_keterampilan')->updateOrInsert(['id_nilai_keterampilan' => 1000003], ['id_nilai_keterampilan' => 1000003, 'nilai' => 89, 'created_at' => now(), 'updated_at' => now()]);

        echo "Nilai keterampilan imported\n";

        // Import nilai (main grades table)
        DB::table('tb_nilai')->updateOrInsert(
            ['id_nilai' => 1000001],
            ['id_user' => 1002, 'id_nilai' => 1000001, 'id_nilai_harian' => 1000000001, 'id_nilai_keterampilan' => 1000001, 'id_nilai_ulangan' => 1000001, 'id_nilai_ujian' => 1000001, 'id_subjects' => 'S001', 'semester' => '2/24', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai')->updateOrInsert(
            ['id_nilai' => 1000002],
            ['id_user' => 1002, 'id_nilai' => 1000002, 'id_nilai_harian' => 1000000002, 'id_nilai_keterampilan' => 1000002, 'id_nilai_ulangan' => 1000001, 'id_nilai_ujian' => 1000001, 'id_subjects' => 'S001', 'semester' => '3/24', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai')->updateOrInsert(
            ['id_nilai' => 1000003],
            ['id_user' => 1002, 'id_nilai' => 1000003, 'id_nilai_harian' => 1000000003, 'id_nilai_keterampilan' => 1000002, 'id_nilai_ulangan' => 1000001, 'id_nilai_ujian' => 1000001, 'id_subjects' => 'S001', 'semester' => '4/24', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('tb_nilai')->updateOrInsert(
            ['id_nilai' => 1000004],
            ['id_user' => 1002, 'id_nilai' => 1000004, 'id_nilai_harian' => 1000000004, 'id_nilai_keterampilan' => 1000003, 'id_nilai_ulangan' => 1000002, 'id_nilai_ujian' => 1000001, 'id_subjects' => 'S002', 'semester' => '1/25', 'created_at' => now(), 'updated_at' => now()]
        );

        echo "Nilai (main grades) imported\n";

        echo "\nAll data imported successfully!\n";
    }
}
