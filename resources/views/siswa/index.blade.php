<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - E-RAPOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0A2E5C;
            --primary-blue-hover: #15407D;
            --sidebar-bg: #0A2E5C;
            --sidebar-hover: #1A3E6D;
            --sidebar-active: #E68A00;
            --bg-light: #F4F7F6;
            --text-dark: #333333;
            --text-gray: #666666;
            --text-light: #A0B2C6;
            --white: #FFFFFF;
            --border-color: #E2E8F0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --green: #10B981;
            --orange: #F59E0B;
            --blue: #3B82F6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden;
        }

        .app-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--white);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .logo-icon {
            width: 32px;
            height: 32px;
            background-color: var(--white);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sidebar-bg);
            font-weight: bold;
            font-size: 18px;
        }

        .brand-text h3 {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .brand-text p {
            font-size: 11px;
            color: var(--text-light);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            overflow-y: auto;
            flex-grow: 1;
        }

        .menu-header {
            padding: 10px 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-light);
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .menu-item {
            margin: 4px 12px;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--white);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu-item a:hover {
            background-color: var(--sidebar-hover);
        }

        .menu-item.active a {
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid var(--sidebar-active);
            border-radius: 0 8px 8px 0;
            margin-left: -12px;
            padding-left: 24px;
        }

        .menu-item i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
            font-size: 16px;
            opacity: 0.8;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Topbar */
        .topbar {
            height: 70px;
            background-color: var(--white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .hamburger {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-gray);
            cursor: pointer;
        }

        .topbar-left h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .academic-year {
            text-align: right;
            display: flex;
            flex-direction: column;
        }

        .academic-year span {
            font-size: 11px;
            color: var(--text-gray);
        }

        .academic-year strong {
            font-size: 14px;
            color: var(--primary-blue);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            border-left: 1px solid var(--border-color);
            padding-left: 20px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .user-info strong {
            font-size: 14px;
            color: var(--text-dark);
        }

        .user-info span {
            font-size: 11px;
            color: var(--text-gray);
        }

        .avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-blue);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        /* Content Area */
        .content-wrapper {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-hover));
            border-radius: 12px;
            padding: 30px;
            color: var(--white);
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
        }

        .welcome-banner h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 11px;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .stat-card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            text-align: center;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-gray);
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-value {
            font-size: 48px;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .stat-subtitle {
            font-size: 12px;
            color: var(--text-gray);
        }

        /* Grades Table Card */
        .card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header h3 {
            font-size: 18px;
            color: var(--primary-blue);
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #000;
            color: white;
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        tbody tr:hover {
            background-color: var(--bg-light);
        }

        .predikat {
            font-weight: 700;
            font-size: 16px;
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="logo-icon">E</div>
                <div class="brand-text">
                    <h3>E-RAPOR</h3>
                    <p>Deep Learning 2026</p>
                </div>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-header">MENU UTAMA</li>
                <li class="menu-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon">📊</i> Dashboard
                    </a>
                </li>
                <li class="menu-item active">
                    <a href="{{ route('siswa.index') }}">
                        <i class="icon">📝</i> Nilai Raport
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('siswa.profile') }}">
                        <i class="icon">👤</i> Profil
                    </a>
                </li>
            </ul>

            <div style="padding: 20px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 500; transition: background 0.2s;">
                        <i class="icon">↪</i> Keluar
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="hamburger">☰</button>
                    <h2>Dashboard Siswa</h2>
                </div>
                <div class="topbar-right">
                    <div class="academic-year">
                        <span>Tahun Pelajaran</span>
                        <strong>{{ $siswaInfo['tahun_ajaran'] }} ({{ $siswaInfo['semester'] }})</strong>
                    </div>
                    <div class="user-profile">
                        <div class="user-info">
                            <strong>{{ $siswaInfo['nama'] }}</strong>
                            <span>NIS: {{ $siswaInfo['nis'] }}</span>
                        </div>
                        <div class="avatar">
                            {{ substr($siswaInfo['nama'], 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <h1>Selamat Datang, {{ $siswaInfo['nama'] }}</h1>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">NIS</span>
                            <span class="info-value">{{ $siswaInfo['nis'] }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas</span>
                            <span class="info-value">{{ $siswaInfo['kelas'] }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Wali Kelas</span>
                            <span class="info-value">{{ $siswaInfo['wali_kelas'] }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Rata-rata Nilai</div>
                        <div class="stat-value">{{ number_format($rataRata, 1) }}</div>
                        <div class="stat-subtitle">dari 100</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Peringkat Kelas</div>
                        <div class="stat-value">{{ $peringkatKelas }}</div>
                        <div class="stat-subtitle">dari {{ $totalSiswa }} siswa</div>
                    </div>
                </div>
                
                <!-- Grades Table -->
                <div class="card">
                    <div class="card-header">
                        <h3>Daftar Nilai Semester {{ $siswaInfo['semester'] }}</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>MATA PELAJARAN</th>
                                <th>NILAI PENGETAHUAN</th>
                                <th>NILAI KETERAMPILAN</th>
                                <th>NILAI AKHIR</th>
                                <th>PREDIKAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilai as $n)
                            <tr>
                                <td><strong>{{ $n->mapel }}</strong></td>
                                <td>{{ $n->nilai_pengetahuan }}</td>
                                <td>{{ $n->nilai_keterampilan }}</td>
                                <td><strong>{{ $n->nilai_akhir }}</strong></td>
                                <td><span class="predikat">{{ $n->predikat }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
