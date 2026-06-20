<?php

return [
    'users' => [
        'columns' => [
            'id_user' => 'ID User',
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            'phone_number' => 'No. Telepon',
            'gender' => 'Jenis Kelamin (M/F)',
        ],
        'required' => ['id_user', 'name'],
        'validation' => [
            'id_user' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:M,F',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ],
    ],
    'siswa' => [
        'columns' => [
            'nis' => 'NIS',
            'name' => 'Nama Lengkap',
            'nisn' => 'NISN',
            'entry_year' => 'Tahun Masuk',
            'class_name' => 'Nama Kelas',
            'gender' => 'Jenis Kelamin (L/P)',
        ],
        'required' => ['nis', 'name'],
        'validation' => [
            'nis' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'entry_year' => 'nullable|integer|min:2000|max:2100',
            'class_name' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P,M,F',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE3F2FD',
            'font_color' => 'FF1976D2',
        ],
    ],
    'guru' => [
        'columns' => [
            'nomor_induk' => 'Nomor Induk',
            'name' => 'Nama Lengkap',
            'type' => 'Tipe (pns/honorer)',
            'date_of_employment' => 'Tahun Masuk Kerja',
            'teacher_status' => 'Status (employed/resigned)',
            'gender' => 'Jenis Kelamin (L/P)',
        ],
        'required' => ['nomor_induk', 'name'],
        'validation' => [
            'nomor_induk' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'type' => 'nullable|in:pns,honorer',
            'date_of_employment' => 'nullable|integer|min:1950|max:2100',
            'teacher_status' => 'nullable|in:employed,resigned',
            'gender' => 'nullable|in:L,P,M,F',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFDCEDC8',
            'font_color' => 'FF689F38',
        ],
    ],
    'mapel' => [
        'columns' => [
            'code' => 'Kode Mapel',
            'name' => 'Nama Mata Pelajaran',
            'grade' => 'Tingkat Kelas',
            'curriculum' => 'Kurikulum',
            'group' => 'Kelompok',
        ],
        'required' => ['name'],
        'validation' => [
            'code' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'grade' => 'nullable|integer|min:1|max:12',
            'curriculum' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFFFF3E0',
            'font_color' => 'FFF57C00',
        ],
    ],
    'kelas' => [
        'columns' => [
            'name' => 'Nama Kelas',
            'grade' => 'Tingkat',
        ],
        'required' => ['name'],
        'validation' => [
            'name' => 'required|string|max:50',
            'grade' => 'nullable|integer|min:1|max:12',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFF3E5F5',
            'font_color' => 'FF7B1FA2',
        ],
    ],
];
