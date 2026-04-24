<!DOCTYPE html>
<html>
<head>
    <title>E-RAPOR - Electronic Report Card System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 450px;
            text-align: center;
            background: white;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 2.5em;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #777;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .description {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 35px;
            text-align: left;
        }

        .description ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .description li {
            margin-bottom: 8px;
        }

        .login-button {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: #6c63ff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background: #574fd6;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 2em;
            }
            
            .description {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>E-RAPOR</h1>
    <p class="subtitle">Sistem Informasi Raport Elektronik</p>
    
    <div class="description">
        <p>Selamat datang! E-RAPOR adalah website didesain untuk memudahkan pengelolaan nilai rapor dalam lingkungan sekolah.</p>
        
        <p><strong>Fitur Utama:</strong></p>
        <ul>
            <li>manajemen data siswa</li>
            <li>pelacakan kemajuan akademik siswa</li>
            <li>alat untuk guru dan administrator</li>
            <li>akses aman untuk siswa, guru, dan administrator</li>
        </ul>
        
        <p>Silakan masuk untuk mengakses akun Anda dan mulai menggunakan sistem.</p>
    </div>

    <a href="/login" class="login-button">Login to E-RAPOR</a>
</div>

</body>
</html>