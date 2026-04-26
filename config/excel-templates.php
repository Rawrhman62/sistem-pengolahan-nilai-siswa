<?php

return [
    'users' => [
        'columns' => [
            'name' => 'Nama Lengkap',
            'user_name' => 'Username',
            'user_id' => 'User ID',
            'email' => 'Email',
            'phone_number' => 'No. Telepon',
            'role' => 'Role',
        ],
        'required' => ['name', 'user_name', 'user_id', 'email', 'role'],
        'validation' => [
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users,user_name',
            'user_id' => 'required|string|max:255|unique:users,user_id',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:admin,lectureTeacher,homeroomTeacher',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ],
    ],

    'siswa' => [
        'columns' => [
            'nama' => 'Nama Siswa',
            'nis' => 'NIS',
            'kelas' => 'Kelas',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tanggal_lahir' => 'Tanggal Lahir',
        ],
        'required' => ['nama', 'nis', 'kelas'],
        'validation' => [
            'nama' => 'required|string|max:255',
            'nis' => 'required|numeric|digits:10|unique:siswa,nis',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ],
    ],

    'guru' => [
        'columns' => [
            'nama' => 'Nama Guru',
            'nip' => 'NIP',
            'mapel' => 'Mata Pelajaran',
            'email' => 'Email',
            'phone_number' => 'No. Telepon',
        ],
        'required' => ['nama', 'nip'],
        'validation' => [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:guru,nip',
            'mapel' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ],
    ],

    'mapel' => [
        'columns' => [
            'kode' => 'Kode Mapel',
            'nama' => 'Nama Mata Pelajaran',
            'kelompok' => 'Kelompok',
            'kkm' => 'KKM',
        ],
        'required' => ['kode', 'nama', 'kelompok'],
        'validation' => [
            'kode' => 'required|string|max:20|unique:mapel,kode',
            'nama' => 'required|string|max:255',
            'kelompok' => 'required|string|max:100',
            'kkm' => 'nullable|numeric|min:0|max:100',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ],
    ],

    'kelas' => [
        'columns' => [
            'nama' => 'Nama Kelas',
            'tingkat' => 'Tingkat',
            'wali_kelas' => 'Wali Kelas',
            'jurusan' => 'Jurusan',
            'tahun_ajaran' => 'Tahun Ajaran',
        ],
        'required' => ['nama', 'tingkat'],
        'validation' => [
            'nama' => 'required|string|max:50|unique:kelas,nama',
            'tingkat' => 'required|integer|min:1|max:12',
            'wali_kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:100',
            'tahun_ajaran' => 'nullable|string|max:20',
        ],
        'header_style' => [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ],
    ],
];
