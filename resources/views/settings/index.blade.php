<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - E-RAPOR</title>
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
            max-width: 800px;
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

        .info-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
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

        .btn-outline {
            background-color: transparent;
            color: #3B82F6;
            border: 2px solid #3B82F6;
        }

        .btn-outline:hover {
            background-color: #3B82F6;
            color: white;
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

        .profile-preview-container {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">
            &larr; Kembali ke Dashboard
        </a>

        <div class="header">
            <h1>Pengaturan Profil</h1>
            <p>Kelola informasi profil Anda</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                Ada kesalahan pada input Anda:
                <ul style="margin-left: 20px; margin-top: 5px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Picture Section -->
            <div class="section">
                <h2 class="section-title">Foto Profil</h2>
                
                <div class="profile-preview-container">
                    @php
                        $imagePath = $user->profile_picture;
                        if ($imagePath && strpos($imagePath, '/public/') === 0) {
                            $imagePath = substr($imagePath, 7);
                        }
                        $imagePath = $imagePath ?: 'images/default-avatar.png';
                    @endphp
                    <img id="profile-preview" src="{{ asset($imagePath) }}" alt="Profile" 
                        style="width: 90px; height: 120px; border-radius: 8px; object-fit: cover; border: 3px solid #e5e7eb;"
                        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                    <div style="flex: 1;">
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept="image/*">
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

            <!-- Contact Information -->
            <div class="section">
                <h2 class="section-title">Informasi Kontak</h2>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                    <div class="info-text">Email bersifat opsional</div>
                </div>

                <div class="form-group">
                    <label for="phone_number">Nomor Telepon <span style="color: red;">*</span></label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                    <div class="info-text">Nomor telepon bersifat wajib</div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="section">
                <h2 class="section-title">Keamanan</h2>
                
                <div class="form-group">
                    <label>Password</label>
                    <a href="{{ route('settings.password') }}" class="btn btn-outline" style="display: inline-block; text-decoration: none;">
                        🔒 Ubah Password
                    </a>
                    <div class="info-text">Klik tombol di atas untuk mengubah password Anda</div>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
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
    </script>
</body>
</html>
