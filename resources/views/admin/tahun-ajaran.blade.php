<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tahun Ajaran - E-RAPOR</title>
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
            --green-light: #D1FAE5;
            --green-text: #047857;
            --orange: #E67E22;
            --blue: #1976D2;
            --btn-purple: #6366F1;
            --btn-purple-hover: #4F46E5;
            --gray-light: #F3F4F6;
            --gray-badge: #E5E7EB;
            --gray-text: #6B7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--white);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden;
        }

        .app-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles (Reused from previous views) */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--white);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            flex-shrink: 0;
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
            font-style: normal;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: var(--white);
        }

        /* Topbar */
        .topbar {
            height: 70px;
            background-color: var(--white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid var(--border-color);
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
            color: var(--text-dark);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 30px;
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
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        
        .page-title h1 {
            font-size: 22px;
            color: var(--primary-blue);
            margin-bottom: 6px;
            font-weight: 700;
        }
        
        .page-title p {
            color: var(--text-gray);
            font-size: 14px;
        }
        
        .btn-add {
            background-color: var(--btn-purple);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        
        .btn-add:hover {
            background-color: var(--btn-purple-hover);
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
            gap: 30px;
        }
        
        .tab-item {
            padding: 12px 0;
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 500;
            cursor: pointer;
            position: relative;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .tab-item:hover {
            color: var(--btn-purple);
        }
        
        .tab-item.active {
            color: var(--btn-purple);
        }
        
        .tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--btn-purple);
        }

        /* Card Container */
        .settings-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            overflow: hidden;
            padding: 24px;
        }

        /* Active Banner */
        .active-banner {
            background-color: #1a2942;
            border-radius: 12px;
            padding: 24px 30px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .banner-content {
            position: relative;
            z-index: 2;
        }

        .banner-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 12px;
        }

        .banner-title {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .banner-title h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }

        .semester-badge {
            background-color: var(--btn-purple);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .banner-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            max-width: 500px;
            line-height: 1.5;
        }

        .banner-icon {
            font-size: 120px;
            opacity: 0.05;
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            line-height: 1;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            font-weight: 500;
            color: var(--text-gray);
            font-size: 13px;
            background-color: #fafbfc;
        }

        .data-table td {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            vertical-align: middle;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-aktif {
            background-color: var(--green-light);
            color: var(--green-text);
        }

        .status-arsip {
            background-color: var(--gray-light);
            color: var(--gray-text);
        }

        /* Actions */
        .action-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-aktifkan {
            background-color: var(--btn-purple);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-aktifkan:hover {
            background-color: var(--btn-purple-hover);
        }

        .btn-edit {
            color: var(--text-gray);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }

        .btn-edit:hover {
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
                <li class="menu-item active">
                    <a href="{{ route('admin.tahun-ajaran') }}">
                        <i class="icon">📅</i> Kelola Tahun Ajaran
                    </a>
                </li>
            </ul>

            <div style="padding: 20px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 500; transition: background 0.2s;">
<<<<<<< HEAD
                        <i class="icon">↪</i> Keluar
=======
                        <i class="icon">↪️</i> Keluar
>>>>>>> a09ca05b7d7227bdeda4a916ea5618a417b7d019
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
                    <h2>E-Rapor Digital</h2>
                </div>
                <div class="topbar-right">
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
                
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <h1>Pengaturan Umum</h1>
                        <p>Kelola Tahun Pelajaran.</p>
                    </div>
                    <button class="btn-add">
                        <span style="font-size: 16px; margin-right: 4px;">+</span> Tambah Data
                    </button>
                </div>
                
                <!-- Tabs -->
                <div class="tabs">
                    <a href="#" class="tab-item active">
                        <span style="font-size: 16px;"></span> Tahun & Semester
                    </a>                    
                </div>
                
                <!-- Content Area -->
                <div class="settings-card">
                    <!-- Dynamic Banner -->
                    <div class="active-banner" id="activeBanner">
                        <div class="banner-content">
                            <div class="banner-header">
                                <span style="font-size: 14px;"></span>PERIODE SISTEM AKTIF
                            </div>
                            <div class="banner-title">
                                <h2 id="bannerTahun">2025/2026</h2>
                                <span class="semester-badge" id="bannerSemester">Semester Genap</span>
                            </div>
                            <div class="banner-desc">
                                Seluruh data rapor, leger, dan input nilai yang dilakukan guru saat ini akan tersimpan pada periode ini.
                            </div>
                        </div>
                        <div class="banner-icon">📅</div>
                    </div>

                    <!-- Table -->
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tahun Pelajaran</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Rows will be rendered by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript to handle interactivity -->
    <script>
        // Initial state
        let periods = [
            { id: 1, tahun: '2025/2026', semester: 'Genap', status: 'Aktif' },
            { id: 2, tahun: '2025/2026', semester: 'Ganjil', status: 'Arsip' },
            { id: 3, tahun: '2024/2025', semester: 'Genap', status: 'Arsip' },
            { id: 4, tahun: '2024/2025', semester: 'Ganjil', status: 'Arsip' }
        ];

        const tableBody = document.getElementById('tableBody');
        const bannerTahun = document.getElementById('bannerTahun');
        const bannerSemester = document.getElementById('bannerSemester');

        // Render table
        function renderTable() {
            tableBody.innerHTML = '';
            
            periods.forEach(period => {
                const tr = document.createElement('tr');
                
                // Status HTML
                let statusHtml = '';
                let actionHtml = '';
                
                if (period.status === 'Aktif') {
                    statusHtml = `<span class="status-badge status-aktif">
                                    <span style="font-size: 10px;">✔</span> AKTIF
                                  </span>`;
                    actionHtml = ``; // No action if already active
                } else {
                    statusHtml = `<span class="status-badge status-arsip">Arsip</span>`;
                    actionHtml = `<button class="btn-aktifkan" onclick="activatePeriod(${period.id})">Aktifkan</button>
                                  <button class="btn-edit" title="Edit">✎</button>`;
                }

                tr.innerHTML = `
                    <td><strong>${period.tahun}</strong></td>
                    <td>${period.semester}</td>
                    <td>${statusHtml}</td>
                    <td class="action-cell">${actionHtml}</td>
                `;
                
                tableBody.appendChild(tr);
            });
            
            updateBanner();
        }

        // Update top banner based on active period
        function updateBanner() {
            const activePeriod = periods.find(p => p.status === 'Aktif');
            if (activePeriod) {
                bannerTahun.textContent = activePeriod.tahun;
                bannerSemester.textContent = 'Semester ' + activePeriod.semester;
            }
        }

        // Activate a specific period
        window.activatePeriod = function(id) {
            periods = periods.map(p => {
                if (p.id === id) {
                    return { ...p, status: 'Aktif' };
                } else {
                    return { ...p, status: 'Arsip' };
                }
            });
            renderTable();
        }

        // Initial render
        renderTable();
    </script>
</body>
</html>
