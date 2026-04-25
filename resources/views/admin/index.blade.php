<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-RAPOR</title>
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
            --green: #118A7E;
            --orange: #E67E22;
            --blue: #1976D2;
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

        .menu-item a, .menu-item button {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--white);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .menu-item a:hover, .menu-item button:hover {
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

        .notification {
            position: relative;
            cursor: pointer;
            color: var(--text-gray);
        }

        .notification::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background-color: #E53935;
            border-radius: 50%;
            border: 2px solid var(--white);
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

        .badges {
            display: flex;
            gap: 12px;
        }

        .badge {
            background-color: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            backdrop-filter: blur(4px);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
        }

        .card h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 20px;
            text-align: center;
        }

        /* Pie Chart Simulation */
        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .pie-chart {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(var(--orange) 0% 47%, var(--green) 47% 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .pie-chart::after {
            content: '';
            position: absolute;
            width: 152px;
            height: 152px;
            border-radius: 50%;
            border: 3px solid var(--white);
            box-sizing: border-box;
            background: transparent;
            clip-path: polygon(0 50%, 100% 50%, 100% 47%, 0 47%);
        }

        .legend {
            display: flex;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot.L { background-color: var(--orange); }
        .dot.P { background-color: var(--green); }

        /* Bar Chart */
        .col-span-2 {
            grid-column: span 2;
        }

        .bar-chart {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 10px;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bar-row span {
            width: 60px;
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 500;
        }

        .bar-track {
            flex: 1;
            height: 36px;
            background-color: var(--bg-light);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .bar-fill {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 12px;
            color: var(--white);
            font-weight: 600;
            font-size: 14px;
            border-radius: 6px 0 0 6px;
            transition: width 1s ease-out;
        }

        .bar-fill.green { background-color: var(--green); }
        .bar-fill.orange { background-color: var(--orange); }
        .bar-fill.blue { background-color: var(--blue); }

        /* Activity List */
        .activity-card {
            grid-column: span 3;
        }
        
        .activity-card h3 {
            text-align: left;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .activity-list {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .activity-list li {
            display: flex;
            gap: 15px;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--white);
        }

        .activity-avatar {
            width: 36px;
            height: 36px;
            background-color: #E3F2FD;
            color: var(--blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
            flex-shrink: 0;
        }

        .activity-content strong {
            display: block;
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .activity-content p {
            font-size: 13px;
            color: var(--text-gray);
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .activity-content p a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
        }

        .activity-content span {
            font-size: 11px;
            color: var(--text-light);
        }
        
        /* Icons (Simple unicode for now, ideally use FontAwesome or similar) */
        .icon {
            font-style: normal;
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
                    <h3>E-RAPOR </h3>
                    <p>Deep Learning 2026</p>
                </div>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-header">MENU UTAMA</li>
                <li class="menu-item active">
                    <a href="{{ route('admin.index') }}">
                        <i class="icon">📊</i> Dashboard
                    </a>
                </li>
                
                <li class="menu-header">MASTER DATA</li>
                <li class="menu-item">
                    <a href="{{ route('admin.sekolah') }}">
                        <i class="icon">📁</i> Kelola Data
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.manage') }}">
                        <i class="icon">👥</i> Manajemen User
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.tahun-ajaran') }}">
                        <i class="icon">📅</i> Kelola Tahun Ajaran
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
                    <h2>Dashboard Administrator</h2>
                </div>
                <div class="topbar-right">
                    <div class="academic-year">
                        <span>Tahun Pelajaran</span>
                        <strong>2025/2026 (Genap)</strong>
                    </div>
                    <div class="notification">
                        <i class="icon">🔔</i>
                    </div>
                    <div class="user-profile">
                        <div class="user-info">
                            <strong>{{ auth()->user()->user_id ?? 'Admin Sekolah' }}</strong>
                            <span>ADMINISTRATOR</span>
                        </div>
                        <div class="avatar">
                            {{ substr(auth()->user()->user_id ?? 'AS', 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <h1>Selamat Datang, Administrator</h1>
                    <div class="badges">
                        <span class="badge">Kurikulum Deep Learning 2026</span>
                        <span class="badge">DIGITAL</span>
                    </div>
                </div>
                
                <!-- Cards Row -->
                <div class="dashboard-grid">
                    <!-- Statistik Siswa -->
                    <div class="card">
                        <h3>Statistik Siswa</h3>
                        <div class="chart-container">
                            <div class="pie-chart">
                                <div style="position: absolute; z-index: 2; display: flex; flex-direction: column; width: 100%; height: 100%;">
                                    <div style="flex: 1; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; padding-top: 20px;">120</div>
                                    <div style="flex: 1; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; padding-bottom: 20px;">135</div>
                                </div>
                            </div>
                            <div class="legend">
                                <span class="legend-item"><span class="dot L"></span> L</span>
                                <span class="legend-item"><span class="dot P"></span> P</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rerata Nilai -->
                    <div class="card col-span-2">
                        <h3>Rerata Nilai per Tingkat</h3>
                        <div class="bar-chart">
                            <div class="bar-row">
                                <span>Kelas I</span>
                                <div class="bar-track"><div class="bar-fill green" style="width: 88.5%;">88.5</div></div>
                            </div>
                            <div class="bar-row">
                                <span>Kelas IV</span>
                                <div class="bar-track"><div class="bar-fill orange" style="width: 86.2%;">86.2</div></div>
                            </div>
                            <div class="bar-row">
                                <span>Kelas VI</span>
                                <div class="bar-track"><div class="bar-fill blue" style="width: 84.8%;">84.8</div></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Aktivitas Terbaru -->
                    <div class="card activity-card">
                        <h3>⏱ Aktivitas Terbaru</h3>
                        <ul class="activity-list">
                            <li>
                                <div class="activity-avatar">BS</div>
                                <div class="activity-content">
                                    <strong>Budi Santoso, S.Pd.SD</strong>
                                    <p>Menginput nilai <a href="#">Kelas IV A</a> untuk mapel IPAS.</p>
                                    <span>10 menit yang lalu</span>
                                </div>
                            </li>
                            <li>
                                <div class="activity-avatar">BS</div>
                                <div class="activity-content">
                                    <strong>Budi Santoso, S.Pd.SD</strong>
                                    <p>Menginput nilai <a href="#">Kelas IV A</a> untuk mapel IPAS.</p>
                                    <span>10 menit yang lalu</span>
                                </div>
                            </li>
                            <li>
                                <div class="activity-avatar">BS</div>
                                <div class="activity-content">
                                    <strong>Budi Santoso, S.Pd.SD</strong>
                                    <p>Menginput nilai <a href="#">Kelas IV A</a> untuk mapel IPAS.</p>
                                    <span>10 menit yang lalu</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>