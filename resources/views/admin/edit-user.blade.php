<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - E-RAPOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #F4F7F6;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .header h1 {
            color: #0A2E5C;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .alert-error ul {
            margin-left: 20px;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 30px;
            padding: 25px;
            background-color: #f9fafb;
            border-radius: 8px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #0A2E5C;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-admin {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-teacher {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .badge-student {
            background-color: #dcfce7;
            color: #166534;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #3B82F6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563EB;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        .back-link {
            color: #3B82F6;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .info-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('admin.manage') }}" class="back-link">
            &larr; Kembali ke Manajemen User
        </a>

        <div class="header">
            <h1>Edit Pengguna</h1>
            <p>ID User: {{ $user->id_user }} | 
                @foreach($user->getRoles() as $role)
                    <span class="badge 
                        @if($role == 'administrator') badge-admin
                        @elseif(in_array($role, ['lectureTeacher', 'homeroomTeacher'])) badge-teacher
                        @else badge-student
                        @endif
                    ">
                        @if($role == 'administrator') Admin
                        @elseif($role == 'lectureTeacher') Guru Mapel
                        @elseif($role == 'homeroomTeacher') Wali Kelas
                        @else Siswa
                        @endif
                    </span>
                @endforeach
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                Ada kesalahan pada input Anda:
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.user.update', $user->id_user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="section">
                <h2 class="section-title">Informasi Dasar</h2>
                
                <div class="form-group">
                    <label>Foto Profil</label>
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 15px;">
                        @php
                            // Remove /public prefix if it exists for asset() function
                            $imagePath = $user->profile_picture;
                            if ($imagePath && strpos($imagePath, '/public/') === 0) {
                                $imagePath = substr($imagePath, 7); // Remove '/public' from the beginning
                            }
                            $imagePath = $imagePath ?: 'images/default-avatar.png';
                        @endphp
                        <img id="profile-preview" src="{{ asset($imagePath) }}" alt="Profile" 
                            style="width: 90px; height: 120px; border-radius: 8px; object-fit: cover; border: 3px solid #e5e7eb;"
                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                        <div style="flex: 1;">
                            <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept="image/*" style="margin-bottom: 8px;">
                            <div class="info-text">Upload gambar dengan rasio 3:4 (portrait). Format: JPG, PNG, atau GIF (Max: 2MB)</div>
                            @if($user->profile_picture && $user->profile_picture !== '/public/images/')
                                <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px; cursor: pointer;">
                                    <input type="checkbox" name="remove_profile_picture" value="1" style="width: 16px; height: 16px;">
                                    <span style="font-size: 13px; color: #dc2626;">Hapus foto profil</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="Opsional">
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Nomor Telepon</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Opsional">
                    </div>
                </div>
            </div>

            <!-- Password Management -->
            <div class="section">
                <h2 class="section-title">Keamanan Akun</h2>
                
                <div class="form-group">
                    <label>Status Password</label>
                    <div style="margin-bottom: 15px;">
                        @if($user->password)
                            <span class="badge" style="background-color: #dcfce7; color: #166534; padding: 6px 12px;">
                                ✓ Password sudah diset
                            </span>
                        @else
                            <span class="badge" style="background-color: #fef3c7; color: #92400e; padding: 6px 12px;">
                                ⚠ Password belum diset
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                    <div class="info-text">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</div>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Ketik ulang password baru">
                </div>
            </div>

            <!-- Student Information -->
            @if($user->isStudent())
                <div class="section">
                    <h2 class="section-title">Informasi Siswa</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nis">NIS</label>
                            <input type="text" id="nis" name="nis" class="form-control" value="{{ old('nis', $user->student->nis) }}" required>
                            <div class="info-text">Digunakan untuk login</div>
                        </div>

                        <div class="form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" id="nisn" name="nisn" class="form-control" value="{{ old('nisn', $user->student->nisn) }}" placeholder="Opsional">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="entry_year">Tahun Masuk</label>
                            <input type="number" id="entry_year" name="entry_year" class="form-control" value="{{ old('entry_year', $user->student->entry_year) }}" min="2000" max="2100">
                        </div>

                        <div class="form-group">
                            <label for="id_class">Kelas</label>
                            <select id="id_class" name="id_class" class="form-control">
                                <option value="">Tidak Ada Kelas</option>
                                @foreach($classList as $class)
                                    <option value="{{ $class->id_class }}" 
                                        {{ old('id_class', $user->student->id_class) == $class->id_class ? 'selected' : '' }}>
                                        {{ $class->name }} (Grade {{ $class->grade }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="info-text">Pilih kelas untuk siswa ini</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Teacher Information -->
            @if($user->isTeacher())
                <div class="section">
                    <h2 class="section-title">Peran Guru</h2>
                    
                    <div class="form-group">
                        <label>Pilih Peran Guru</label>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                            <label class="checkbox-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_lecturer" value="1" 
                                    {{ old('is_lecturer', $user->isLecturer()) ? 'checked' : '' }}
                                    style="width: 18px; height: 18px; accent-color: #3B82F6;">
                                <span style="font-size: 14px; color: #4b5563;">Guru Mata Pelajaran (Lecturer)</span>
                            </label>
                            <label class="checkbox-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_homeroom" value="1" 
                                    {{ old('is_homeroom', $user->isHomeroomTeacher()) ? 'checked' : '' }}
                                    style="width: 18px; height: 18px; accent-color: #3B82F6;">
                                <span style="font-size: 14px; color: #4b5563;">Wali Kelas (Homeroom Teacher)</span>
                            </label>
                        </div>
                        <div class="info-text">Guru dapat memiliki satu atau kedua peran</div>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title">Informasi Guru</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nomor_induk">Nomor Induk Guru</label>
                            <input type="text" id="nomor_induk" name="nomor_induk" class="form-control" value="{{ old('nomor_induk', $user->teacher->nomor_induk) }}" required>
                            <div class="info-text">Digunakan untuk login</div>
                        </div>

                        <div class="form-group">
                            <label for="type">Status Kepegawaian</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="pns" {{ old('type', $user->teacher->type) == 'pns' ? 'selected' : '' }}>PNS</option>
                                <option value="honorer" {{ old('type', $user->teacher->type) == 'honorer' ? 'selected' : '' }}>Honorer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_of_employment">Tahun Mulai Bekerja</label>
                            <input type="number" id="date_of_employment" name="date_of_employment" class="form-control" value="{{ old('date_of_employment', $user->teacher->date_of_employment) }}" min="1950" max="2100">
                        </div>

                        <div class="form-group">
                            <label for="teacher_status">Status Pekerjaan</label>
                            <select id="teacher_status" name="teacher_status" class="form-control" required>
                                <option value="employed" {{ old('teacher_status', $user->teacher->teacher_status) == 'employed' ? 'selected' : '' }}>Employed (Bekerja)</option>
                                <option value="resigned" {{ old('teacher_status', $user->teacher->teacher_status) == 'resigned' ? 'selected' : '' }}>Resigned (Resign)</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Homeroom Information (shown conditionally with JS) -->
            @if($user->isTeacher())
                <div class="section" id="homeroom-section" style="display: {{ $user->isHomeroomTeacher() ? 'block' : 'none' }};">
                    <h2 class="section-title">Informasi Wali Kelas</h2>
                    
                    <div class="form-group">
                        <label for="homeroom_class">Kelas yang Diampu</label>
                        <select id="homeroom_class" name="homeroom_class" class="form-control">
                            <option value="">Tidak Ada Kelas</option>
                            @foreach($classList as $class)
                                <option value="{{ $class->id_class }}" 
                                    {{ old('homeroom_class', optional($user->homerooms->first())->id_class ?? '') == $class->id_class ? 'selected' : '' }}>
                                    {{ $class->name }} (Grade {{ $class->grade }})
                                </option>
                            @endforeach
                        </select>
                        <div class="info-text">Pilih kelas untuk wali kelas ini</div>
                    </div>
                </div>
            @endif

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.manage') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        // Profile picture preview
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Show/hide homeroom section based on checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const homeroomCheckbox = document.querySelector('input[name="is_homeroom"]');
            const homeroomSection = document.getElementById('homeroom-section');
            
            if (homeroomCheckbox && homeroomSection) {
                function toggleHomeroomSection() {
                    if (homeroomCheckbox.checked) {
                        homeroomSection.style.display = 'block';
                    } else {
                        homeroomSection.style.display = 'none';
                    }
                }
                
                homeroomCheckbox.addEventListener('change', toggleHomeroomSection);
                toggleHomeroomSection(); // Initial state
            }
        });
    </script>
</body>
</html>
