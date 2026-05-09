<?php

return array (
  'users' => 
  array (
    'columns' => 
    array (
      'name' => 'Nama Lengkap',
      'user_name' => 'Username',
      'user_id' => 'NIS',
      'email' => 'Email',
      'phone_number' => 'No. Telepon',
      'role' => 'Sebagai',
    ),
    'required' => 
    array (
      0 => 'name',
      1 => 'user_name',
      2 => 'user_id',
      3 => 'email',
      4 => 'role',
    ),
    'validation' => 
    array (
      'name' => 'required|string|max:255',
      'user_name' => 'required|string|max:255|unique:users,user_name',
      'user_id' => 'required|string|max:255|unique:users,user_id',
      'email' => 'required|email|max:255|unique:users,email',
      'phone_number' => 'nullable|string|max:20',
      'role' => 'required|in:admin,lectureTeacher,homeroomTeacher',
    ),
    'header_style' => 
    array (
      'font_bold' => true,
      'background_color' => 'FFE2E8F0',
      'font_color' => 'FF0A2E5C',
    ),
  ),
  'siswa' => 
  array (
    'columns' => 
    array (
      'nama' => 'Nama Siswa',
      'nis' => 'NIS',
      'kelas' => 'Kelas',
      'jenis_kelamin' => 'Jenis Kelamin',
      'tanggal_lahir' => 'Tanggal Lahir',
    ),
    'required' => 
    array (
      0 => 'nama',
      1 => 'nis',
      2 => 'kelas',
    ),
    'validation' => 
    array (
      'nama' => 'required|string|max:255',
      'nis' => 'required|numeric|digits:10|unique:siswa,nis',
      'kelas' => 'required|string|max:50',
      'jenis_kelamin' => 'nullable|in:L,P',
      'tanggal_lahir' => 'nullable|date',
    ),
    'header_style' => 
    array (
      'font_bold' => true,
      'background_color' => 'FFE2E8F0',
      'font_color' => 'FF0A2E5C',
    ),
  ),
  'guru' => 
  array (
    'columns' => 
    array (
      'nama' => 'Nama Guru',
      'nip' => 'NIP',
      'mapel' => 'Mata Pelajaran',
      'email' => 'Email',
      'phone_number' => 'No. Telepon',
    ),
    'required' => 
    array (
      0 => 'nama',
      1 => 'nip',
    ),
    'validation' => 
    array (
      'nama' => 'required|string|max:255',
      'nip' => 'required|string|max:50|unique:guru,nip',
      'mapel' => 'nullable|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone_number' => 'nullable|string|max:20',
    ),
    'header_style' => 
    array (
      'font_bold' => true,
      'background_color' => 'FFE2E8F0',
      'font_color' => 'FF0A2E5C',
    ),
  ),
  'mapel' => 
  array (
    'columns' => 
    array (
      'kode' => 'Kode Mapel',
      'nama' => 'Nama Mata Pelajaran',
      'kelompok' => 'Kelompok',
      'kkm' => 'KKM',
    ),
    'required' => 
    array (
      0 => 'kode',
      1 => 'nama',
      2 => 'kelompok',
    ),
    'validation' => 
    array (
      'kode' => 'required|string|max:20|unique:mapel,kode',
      'nama' => 'required|string|max:255',
      'kelompok' => 'required|string|max:100',
      'kkm' => 'nullable|numeric|min:0|max:100',
    ),
    'header_style' => 
    array (
      'font_bold' => true,
      'background_color' => 'FFE2E8F0',
      'font_color' => 'FF0A2E5C',
    ),
  ),
  'kelas' => 
  array (
    'columns' => 
    array (
      'nama' => 'Nama Kelas',
      'tingkat' => 'Tingkat',
      'wali_kelas' => 'Wali Kelas',
      'jurusan' => 'Jurusan',
      'tahun_ajaran' => 'Tahun Ajaran',
    ),
    'required' => 
    array (
      0 => 'nama',
      1 => 'tingkat',
    ),
    'validation' => 
    array (
      'nama' => 'required|string|max:50|unique:kelas,nama',
      'tingkat' => 'required|integer|min:1|max:12',
      'wali_kelas' => 'nullable|string|max:255',
      'jurusan' => 'nullable|string|max:100',
      'tahun_ajaran' => 'nullable|string|max:20',
    ),
    'header_style' => 
    array (
      'font_bold' => true,
      'background_color' => 'FFE2E8F0',
      'font_color' => 'FF0A2E5C',
    ),
  ),
);
