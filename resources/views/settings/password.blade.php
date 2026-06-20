<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - E-RAPOR</title>
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

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
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
            width: 100%;
            text-align: center;
        }

        .btn-primary {
            background-color: #3B82F6;
            color: white;
        }

        .btn-primary:hover {
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

        .info-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .password-requirements {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .password-requirements ul {
            margin-left: 20px;
            margin-top: 8px;
            color: #4b5563;
        }

        .password-requirements li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ganti Kata Sandi</h1>
            @if(!$user->password_set)
                <p>Buat kata sandi untuk login pertama kali</p>
            @else
                <p>Ubah kata sandi akun Anda</p>
            @endif
        </div>

        @if(!$user->password_set)
            <div class="alert alert-warning">
                ⚠ Anda belum menyetel kata sandi. Silakan membuat kata sandi.
            </div>
        @endif

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

        <div class="password-requirements">
            <strong>Persyaratan Password:</strong>
            <ul>
                <li>Minimal 6 karakter</li>
                <li>Harus mengandung huruf</li>
                <li>Harus mengandung angka</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('settings.password.update') }}">
            @csrf
            @method('PUT')

            @if($user->password_set)
                <div class="form-group">
                    <label for="current_password">Kata Sandi Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="Masukkan password saat ini">
                </div>
            @endif

            <div class="form-group">
                <label for="password">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password baru">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Ulangi Kata Sandi Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Ketik ulang password baru">
            </div>

            <button type="submit" class="btn btn-primary">
                @if(!$user->password_set)
                    Buat Password
                @else
                    Ubah Password
                @endif
            </button>
        </form>

        <a href="{{ route('settings') }}" class="back-link">&larr; Kembali ke Pengaturan</a>
    </div>
</body>
</html>
