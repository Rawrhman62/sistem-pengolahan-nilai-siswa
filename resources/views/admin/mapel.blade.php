<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Mapel - E-RAPOR</title>
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
            --btn-gray: #E2E8F0;
            --btn-blue: #3B82F6;
            --btn-green: #10B981;
            --btn-purple: #6366F1;
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
            color: var(--text-dark);
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
            background-color: var(--white);
        }
        
        /* Master Data Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
        }
        
        .page-title h1 {
            font-size: 24px;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }
        
        .page-title p {
            color: var(--text-gray);
            font-size: 14px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-decoration: none;
            color: var(--white);
            transition: opacity 0.2s;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn-gray { background-color: var(--btn-gray); color: var(--text-dark); }
        .btn-blue { background-color: var(--btn-blue); }
        .btn-green { background-color: var(--btn-green); }
        .btn-purple { background-color: var(--btn-purple); }

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

        /* Table Card Styles */
        .table-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: var(--white);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            font-size: 13px;
            color: var(--text-gray);
            font-weight: 600;
            background-color: #F8FAFC;
        }

        .data-table td {
            font-size: 14px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .action-cell {
            display: flex;
            gap: 16px;
            justify-content: flex-end;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .action-btn:hover {
            opacity: 0.7;
        }

        .btn-edit {
            color: var(--btn-blue);
        }

        .btn-delete {
            color: #E53935;
        }

        /* Icons */
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
                <li class="menu-item active">
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
                
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <h1>Master Data</h1>
                        <p>Kelola data referensi sekolah secara menyeluruh.</p>
                    </div>
                    <div class="action-buttons">                        
                        <button class="btn btn-green"><i class="icon">📊</i> Import Excel</button>
<<<<<<< HEAD
                        <button class="btn btn-purple"><i class="icon">+</i> Tambah</button>
=======
                        <button class="btn btn-purple"><i class="icon">➕</i> Tambah</button>
>>>>>>> a09ca05b7d7227bdeda4a916ea5618a417b7d019
                    </div>
                </div>
                
                <!-- Tabs -->
                <div class="tabs">
                    <a href="{{ route('admin.sekolah') }}" class="tab-item">Sekolah</a>
                    <a href="{{ route('admin.siswa') }}" class="tab-item">Siswa</a>
                    <a href="{{ route('admin.guru') }}" class="tab-item">Guru</a>
                    <a href="{{ route('admin.mapel') }}" class="tab-item active">Mapel</a>
                    <a href="{{ route('admin.kelas') }}" class="tab-item">Kelas</a>
                </div>
                
                <!-- Table Area -->
                <div class="table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelompok</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="editable">MP-01</td>
                                <td class="editable">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 28px; height: 28px; background: #FFF4E5; color: var(--orange); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px;">📙</div>
                                        <span>Pendidikan Agama dan Budi Pekerti</span>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span style="background-color: #E3F2FD; color: var(--btn-blue); border: 1px solid #BBDEFB; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Wajib</span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="editable">MP-02</td>
                                <td class="editable">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 28px; height: 28px; background: #FFF4E5; color: var(--orange); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px;">📙</div>
                                        <span>Pendidikan Pancasila</span>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span style="background-color: #E3F2FD; color: var(--btn-blue); border: 1px solid #BBDEFB; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Wajib</span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="editable">MP-03</td>
                                <td class="editable">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 28px; height: 28px; background: #FFF4E5; color: var(--orange); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px;">📙</div>
                                        <span>Bahasa Indonesia</span>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span style="background-color: #E3F2FD; color: var(--btn-blue); border: 1px solid #BBDEFB; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Wajib</span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="editable">MP-04</td>
                                <td class="editable">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 28px; height: 28px; background: #FFF4E5; color: var(--orange); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px;">📙</div>
                                        <span>Matematika</span>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span style="background-color: #E3F2FD; color: var(--btn-blue); border: 1px solid #BBDEFB; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Wajib</span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="editable">MP-05</td>
                                <td class="editable">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 28px; height: 28px; background: #FFF4E5; color: var(--orange); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px;">📙</div>
                                        <span>IPAS</span>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span style="background-color: #E3F2FD; color: var(--btn-blue); border: 1px solid #BBDEFB; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Wajib</span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="editable">MP-06</td>
                                <td class="editable">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 28px; height: 28px; background: #FFF4E5; color: var(--orange); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px;">📙</div>
                                        <span>PJOK</span>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span style="background-color: #E3F2FD; color: var(--btn-blue); border: 1px solid #BBDEFB; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Wajib</span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit functionality
            const editButtons = document.querySelectorAll('.btn-edit');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const isEditing = row.classList.contains('editing');
                    
                    if (isEditing) {
                        // Save the data
                        const inputs = row.querySelectorAll('input');
                        inputs.forEach(input => {
                            const td = input.closest('td');
                            // Check if this TD had a span inside
                            const spanElement = td.querySelector('span.editable-text');
                            if (spanElement) {
                                spanElement.innerText = input.value;
                                input.remove();
                                spanElement.style.display = '';
                            } else {
                                td.innerText = input.value;
                            }
                        });
                        row.classList.remove('editing');
                        this.innerHTML = '<i class="icon">✏️</i>';
                        this.style.color = 'var(--btn-blue)';
                    } else {
                        // Switch to edit mode
                        const cells = row.querySelectorAll('td.editable');
                        cells.forEach(cell => {
                            const spanElement = cell.querySelector('span');
                            if (spanElement) {
                                spanElement.classList.add('editable-text');
                                const currentText = spanElement.innerText.trim();
                                spanElement.style.display = 'none';
                                cell.insertAdjacentHTML('beforeend', `<input type="text" value="${currentText}" style="width: calc(100% - 30px); padding: 6px; border: 1px solid var(--border-color); border-radius: 4px; font-family: inherit; font-size: 14px;">`);
                            } else {
                                const currentText = cell.innerText.trim();
                                cell.innerHTML = `<input type="text" value="${currentText}" style="width: 100%; padding: 6px; border: 1px solid var(--border-color); border-radius: 4px; font-family: inherit; font-size: 14px;">`;
                            }
                        });
                        row.classList.add('editing');
                        this.innerHTML = '<i class="icon">💾</i>';
                        this.style.color = 'var(--green)';
                    }
                });
            });
        });
    </script>
</body>
</html>
