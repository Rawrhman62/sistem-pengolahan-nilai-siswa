<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru - E-RAPOR</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #0A2E5C;
            font-size: 24px;
            font-weight: 600;
        }

        .header p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            color: #4b5563;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            margin-top: 3px;
            width: 16px;
            height: 16px;
            accent-color: #3B82F6;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #3B82F6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #2563EB;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #3B82F6;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tambah Pengguna Baru</h1>
            <p>Isi formulir di bawah untuk mendaftarkan pengguna baru.</p>
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
        
        <form method="POST" action="{{ route('admin.register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Rafi Said">
            </div>
            
            <div class="form-group">
                <label for="gender">Jenis Kelamin <span style="color: red;">*</span></label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="phone_number">Nomor Telepon <span style="color: red;">*</span></label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="Wajib">
                <small style="color: #6b7280; font-size: 12px;">Masukkan Nomor telepon Orang tua jika mengisi data siswa.</small>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Contoh: user@example.com (opsional)">
                <small style="color: #6b7280; font-size: 12px;">Email bersifat opsional.</small>
            </div>
            
            <div class="form-group">
                <label for="role">Peran <span style="color: red;">*</span></label>
                <select id="role" name="role" class="form-control" required>
                    <option value="">Pilih Peran</option>
                    <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                    <option value="lectureTeacher" {{ old('role') == 'lectureTeacher' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                    <option value="homeroomTeacher" {{ old('role') == 'homeroomTeacher' ? 'selected' : '' }}>Wali Kelas</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            
            <div class="form-group" id="teacher-fields" style="display: none;">
                <label for="nomor_induk">Nomor Induk Guru <span style="color: red;">*</span></label>
                <input type="text" id="nomor_induk" name="nomor_induk" class="form-control" value="{{ old('nomor_induk') }}" placeholder="Contoh: 198501012010011001">
                <small style="color: #6b7280; font-size: 12px;">Nomor induk digunakan untuk login.</small>
                
                <label for="type" style="margin-top: 15px;">Status Kepegawaian <span style="color: red;">*</span></label>
                <select id="type" name="type" class="form-control">
                    <option value="">Pilih Status</option>
                    <option value="pns" {{ old('type') == 'pns' ? 'selected' : '' }}>PNS</option>
                    <option value="honorer" {{ old('type') == 'honorer' ? 'selected' : '' }}>Honorer</option>
                </select>
            </div>
            
            <div class="form-group" id="student-fields" style="display: none;">
                <label for="nis">NIS</label>
                <input type="text" id="nis" name="nis" class="form-control" value="{{ old('nis') }}" placeholder="Kosongkan untuk generate otomatis (contoh: 240001)">
                <small style="color: #6b7280; font-size: 12px;">NIS digunakan untuk login. Biarkan kosong untuk generate otomatis berdasarkan tahun masuk.</small>
                
                <label for="nisn" style="margin-top: 15px;">NISN <span style="color: red;">*</span></label>
                <input type="text" id="nisn" name="nisn" class="form-control" value="{{ old('nisn') }}" placeholder="Wajib">
                <small style="color: #6b7280; font-size: 12px;"></small>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="dual_teacher" id="dual_teacher" value="1" {{ old('dual_teacher') ? 'checked' : '' }}>
                    <span>Guru Mapel sekaligus Wali Kelas</span>
                </label>
            </div>
            
            <button type="submit" class="btn-submit">Daftarkan Pengguna</button>
        </form>
        
        <a href="{{ route('admin.manage') }}" class="back-link">&larr; Kembali ke Manajemen User</a>

        <script>
            // Show/hide conditional fields based on role
            document.getElementById('role').addEventListener('change', function() {
                const role = this.value;
                const teacherFields = document.getElementById('teacher-fields');
                const studentFields = document.getElementById('student-fields');
                const dualTeacherCheckbox = document.getElementById('dual_teacher').parentElement.parentElement;
                const nomorIndukInput = document.getElementById('nomor_induk');
                const typeInput = document.getElementById('type');
                const nisInput = document.getElementById('nis');
                const nisnInput = document.getElementById('nisn');
                
                // Hide all conditional fields and remove required
                teacherFields.style.display = 'none';
                studentFields.style.display = 'none';
                dualTeacherCheckbox.style.display = 'none';
                nomorIndukInput.removeAttribute('required');
                typeInput.removeAttribute('required');
                nisInput.removeAttribute('required');
                nisnInput.removeAttribute('required');
                
                // Show relevant fields and add required
                if (role === 'lectureTeacher' || role === 'homeroomTeacher') {
                    teacherFields.style.display = 'block';
                    nomorIndukInput.setAttribute('required', 'required');
                    typeInput.setAttribute('required', 'required');
                    dualTeacherCheckbox.style.display = 'block';
                } else if (role === 'student') {
                    studentFields.style.display = 'block';
                    // NIS and NISN are optional (NIS can be auto-generated)
                } else if (role === 'administrator') {
                    teacherFields.style.display = 'block';
                    nomorIndukInput.setAttribute('required', 'required');
                    typeInput.setAttribute('required', 'required');
                }
            });
            
            // Trigger on page load if there's an old value
            if (document.getElementById('role').value) {
                document.getElementById('role').dispatchEvent(new Event('change'));
            }
        </script>
    </div>
</body>
</html>