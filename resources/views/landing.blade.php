<!DOCTYPE html>
<html>
<head>
    <title>E-RAPOR - Electronic Report Card System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/landing_wall.jpg') }}') no-repeat center center fixed;
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
            background: rgba(15, 35, 49, 0.38);
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

        /* Team Section */
        .team-section {
            padding: 180px 50px 100px 50px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .team-section h2 {
            font-size: 36px;
            color: white;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .team-section p.subtitle {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 50px;
            font-size: 18px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .team-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            transition: transform 0.3s, background 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .team-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
        }

        .photo-placeholder {
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 40px;
        }

        .team-name {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .team-role {
            color: #5c9bffff;
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Footer */
        .footer {
            background: rgba(15, 35, 49, 0.38);
            backdrop-filter: blur(10px);
            padding: 40px 50px;
            text-align: center;
            color: white;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 50px;
        }

        .footer-content p {
            font-size: 14px;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .footer-link:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">E-RAPOR</div>
        <div class="nav-menu">
            <a href="#tim-pengembang" class="nav-link">Tentang Kami</a>
            <a href="#footer" class="nav-link">Hubungi Kami</a>
            <a href="/login" class="login-btn">Masuk</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="hero-text">
            <h1 class="hero-title">Selamat Datang!</h1>

            <p class="hero-description">
                E-RAPOR, adalah sistem rapor elektronik yang dibuat untuk  
                Mengelola nilai siswa, pantau kemajuan akademik, dan optimalkan administrasi pendidikan 
                dengan platform modern dan ramah pengguna kami yang dirancang khusus untuk sekolah, 
                guru, wali kelas, dan siswa.
            </p>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section" id="tim-pengembang">
        <h2>Tim Pengembang</h2>
        <p class="subtitle">Dibangun oleh:</p>

        <div class="team-grid">
            <!-- Member 1 -->
            <div class="team-card">
                <div class="photo-placeholder">👤</div>
                <div class="team-name">Fazri Rahman</div>
                <div class="team-role">Front End, Back End</div>
            </div>

            <!-- Member 2 -->
            <div class="team-card">
                <div class="photo-placeholder">👤</div>
                <div class="team-name">Muradika Laksamana</div>
                <div class="team-role">Writer</div>
            </div>

            <!-- Member 3 -->
            <div class="team-card">
                <div class="photo-placeholder">👤</div>
                <div class="team-name">Ahmad Rafi' Sa'id</div>
                <div class="team-role">Front End, Back End</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer" id="footer">
        <div class="footer-content">
            <p>&copy; 2026 E-RAPOR Digital. All rights reserved.</p>
            <div class="footer-links">
                <a href="https://www.instagram.com/muradikaa_?igsh=MXF2c3hsN3R5eDZ4" class="footer-link">Instagram</a>
                <a href="#" class="footer-link">Youtube</a>
                <a href="#" class="footer-link">Website</a>
            </div>
        </div>
    </footer>
</body>
</html>