<?php

return array (
  'users' => 
  array (
    'columns' => 
    array (
      'id_user' => 'ID User',
      'name' => 'Nama Lengkap',
      'email' => 'Email',
      'phone_number' => 'No. Telepon',
      'gender' => 'Jenis Kelamin',
    ),
    'required' => 
    array (
      0 => 'id_user',
      1 => 'name',
    ),
    'validation' => 
    array (
      'id_user' => 'required|string|max:255|unique:tb_users,id_user',
      'name' => 'required|string|max:255',
      'email' => 'nullable|email|max:255|unique:tb_users,email',
      'phone_number' => 'nullable|string|max:20',
      'gender' => 'nullable|in:M,F',
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
      'id_user' => 'ID User',
      'nis' => 'NIS',
      'nisn' => 'NISN',
      'entry_year' => 'Tahun Masuk',
      'id_class' => 'ID Kelas',
    ),
    'required' => 
    array (
      0 => 'id_user',
      1 => 'nis',
    ),
    'validation' => 
    array (
      'id_user' => 'required|string|max:255|unique:tb_students,id_user',
      'nis' => 'required|string|max:20|unique:tb_students,nis',
      'nisn' => 'nullable|string|max:20',
      'entry_year' => 'nullable|integer',
      'id_class' => 'nullable|integer',
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
      'id_user' => 'ID User',
      'nomor_induk' => 'Nomor Induk',
      'date_of_employment' => 'Tahun Masuk',
      'teacher_status' => 'Status Guru',
      'type' => 'Tipe',
    ),
    'required' => 
    array (
      0 => 'id_user',
      1 => 'nomor_induk',
    ),
    'validation' => 
    array (
      'id_user' => 'required|string|max:255|unique:tb_teachers,id_user',
      'nomor_induk' => 'required|string|max:50|unique:tb_teachers,nomor_induk',
      'date_of_employment' => 'nullable|integer',
      'teacher_status' => 'nullable|string|max:255',
      'type' => 'nullable|string|max:255',
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
      'id_subjects' => 'ID Mapel',
      'code' => 'Kode Mapel',
      'name' => 'Nama Mata Pelajaran',
      'grade' => 'Tingkat',
      'curriculum' => 'Kurikulum',
      'group' => 'Kelompok',
    ),
    'required' => 
    array (
      0 => 'id_subjects',
      1 => 'name',
    ),
    'validation' => 
    array (
      'id_subjects' => 'required|string|max:255|unique:tb_subjects,id_subjects',
      'code' => 'nullable|string|max:20',
      'name' => 'required|string|max:255',
      'grade' => 'nullable|integer',
      'curriculum' => 'nullable|string|max:100',
      'group' => 'nullable|string|max:100',
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
      'id_class' => 'ID Kelas',
      'name' => 'Nama Kelas',
      'grade' => 'Tingkat',
    ),
    'required' => 
    array (
      0 => 'id_class',
      1 => 'name',
    ),
    'validation' => 
    array (
      'id_class' => 'required|integer|unique:tb_classes,id_class',
      'name' => 'required|string|max:50',
      'grade' => 'nullable|integer|min:1|max:12',
    ),
    'header_style' => 
    array (
      'font_bold' => true,
      'background_color' => 'FFE2E8F0',
      'font_color' => 'FF0A2E5C',
    ),
  ),
);
