<!DOCTYPE html>
<html>
<head>
    <title>E-RAPOR - Electronic Report Card System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('images/landing_wall.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background: rgba(28, 57, 76, 0.38);
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 400;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #5c9bffff;
        }

        .login-btn {
            background: #6c63ff;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #574fd6;
        }

        /* Main Content */
        .main-content {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-text {
            text-align: center;
            max-width: 600px;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-description {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                gap: 20px;
            }

            .main-content {
                padding: 40px 20px;
            }

            .hero-title {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">E-RAPOR</div>
        <div class="nav-menu">
            <a href="#" class="nav-link">Tentang Kami</a>
            <a href="#" class="nav-link">Hubungi Kami</a>
            <a href="/login" class="login-btn">Masuk</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="hero-text">
            <h1 class="hero-title">安巴圖卡姆!</h1>
            <p class="hero-description">
                Selamat datang di E-RAPOR, sistem rapor elektronik yang lengkap. 
                Kelola nilai siswa, pantau kemajuan akademik, dan optimalkan administrasi pendidikan 
                dengan platform modern dan ramah pengguna kami yang dirancang khusus untuk sekolah, 
                guru, dan siswa.
            </p>
        </div>
    </div>
</body>
</html>