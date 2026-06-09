<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/login_wall.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            width: 320px;
            text-align: center;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 5px;
        }

        p {
            color: #777;
            font-size: 14px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #6c63ff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background: #574fd6;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 5px;
        }

        .hint-text {
            color: #666;
            font-size: 12px;
            margin-top: -8px;
            margin-bottom: 8px;
            text-align: left;
        }
    </style>

    <script>
        function updateLoginField() {
            const role = document.getElementById('role_selector').value;
            const loginField = document.getElementById('login_id');
            const loginHint = document.getElementById('login_hint');
            
            if (role === 'student') {
                loginField.placeholder = 'Masukkan NIS';
                loginHint.textContent = 'Gunakan Nomor Induk Siswa (NIS) Anda';
            } else if (role && role !== '' && role !== 'noentry') {
                loginField.placeholder = 'Masukkan Nomor Induk';
                loginHint.textContent = 'Gunakan Nomor Induk Guru Anda';
            } else {
                loginField.placeholder = 'Masukkan ID';
                loginHint.textContent = '';
            }
        }
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="box">
    <h1>E-RAPOR</h1>
    <p>Sistem Informasi Raport Elektronik</p>

    <form method="POST" action="/login">
        @csrf

        <select name="selected_role" id="role_selector" onchange="updateLoginField()" required>
            <option value="noentry">Masuk Sebagai</option>
            <option value="administrator">Administrator</option>
            <option value="lectureTeacher">Guru</option>
            <option value="homeroomTeacher">Wali Kelas</option>
            <option value="student">Siswa</option>
        </select>

        <input type="text" name="login_id" id="login_id" placeholder="Masukkan ID" required>
        <small id="login_hint" class="hint-text"></small>
        
        <input type="password" name="password" placeholder="Masukkan Password">

        <button type="submit">Masuk</button>

        @if ($errors->any())
            <div class="error">
                @if ($errors->has('login_id'))
                    {{ $errors->first('login_id') }}
                @elseif ($errors->has('selected_role'))
                    {{ $errors->first('selected_role') }}
                @else
                    {{ $errors->first() }}
                @endif
            </div>
        @endif
    </form>
</div>

</body>
</html>