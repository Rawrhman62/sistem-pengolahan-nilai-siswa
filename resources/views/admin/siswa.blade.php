<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Siswa - E-RAPOR</title>
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
        
        /* Alert Messages */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.5;
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
        
        .alert-icon {
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .alert-content {
            flex: 1;
        }

        .alert-error .error-details {
            margin-top: 8px;
            padding-left: 12px;
            border-left: 2px solid #f87171;
            white-space: pre-line;
            font-size: 13px;
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
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <span class="alert-icon">✓</span>
                        <div class="alert-content">{{ session('success') }}</div>
                    </div>
                @endif
                
                @if($errors->has('import'))
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠</span>
                        <div class="alert-content">
                            <strong>Import Failed</strong>
                            <div class="error-details">{{ $errors->first('import') }}</div>
                        </div>
                    </div>
                @endif
                
                @if($errors->has('file'))
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠</span>
                        <div class="alert-content">
                            <strong>File Upload Error</strong>
                            <div class="error-details">{{ $errors->first('file') }}</div>
                        </div>
                    </div>
                @endif
                
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <h1>Master Data</h1>
                        <p>Kelola data referensi sekolah secara menyeluruh.</p>
                    </div>
                    <div class="action-buttons">
                        <x-excel-buttons 
                            export-route="admin.siswa.export" 
                            import-route="admin.siswa.import" 
                            template-route="admin.siswa.template" 
                        />
                        <button class="btn btn-purple"><i class="icon">+</i> Tambah</button>
                    </div>
                </div>
                
                <!-- Tabs -->
                <div class="tabs">
                    <a href="{{ route('admin.sekolah') }}" class="tab-item">Sekolah</a>
                    <a href="{{ route('admin.siswa') }}" class="tab-item active">Siswa</a>
                    <a href="{{ route('admin.guru') }}" class="tab-item">Guru</a>
                    <a href="{{ route('admin.mapel') }}" class="tab-item">Mapel</a>
                    <a href="{{ route('admin.kelas') }}" class="tab-item">Kelas</a>
                </div>
                
                <!-- Table Area -->
                <div class="table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <x-sortable-header 
                                    column="nama" 
                                    label="Nama Siswa" 
                                    :current-sort="request('sort')" 
                                    :current-direction="request('direction', 'asc')" 
                                />
                                <x-sortable-header 
                                    column="nis" 
                                    label="NIS" 
                                    :current-sort="request('sort')" 
                                    :current-direction="request('direction', 'asc')" 
                                />
                                <x-sortable-header 
                                    column="kelas" 
                                    label="Kelas" 
                                    :current-sort="request('sort')" 
                                    :current-direction="request('direction', 'asc')" 
                                />
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $index => $s)
                            <tr>
                                <td>{{ $siswa->firstItem() + $index }}</td>
                                <td>{{ $s->nama }}</td>
                                <td>{{ $s->nis }}</td>
                                <td>{{ $s->kelas }}</td>
                                <td>
                                    <div class="action-cell">
                                        <button class="action-btn btn-edit"><i class="icon">✏️</i></button>
                                        <button class="action-btn btn-delete"><i class="icon">🗑️</i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-gray);">
                                    Tidak ada data siswa
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($siswa->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: center;">
                    {{ $siswa->links() }}
                </div>
                @endif
                
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
                            td.innerHTML = input.value;
                        });
                        row.classList.remove('editing');
                        this.innerHTML = '<i class="icon">✏️</i>';
                        this.style.color = 'var(--btn-blue)';
                    } else {
                        // Switch to edit mode
                        const cells = row.querySelectorAll('td');
                        // We skip the first (No) and last (Aksi) columns
                        for (let i = 1; i < cells.length - 1; i++) {
                            const currentText = cells[i].innerText.trim();
                            cells[i].innerHTML = `<input type="text" value="${currentText}" style="width: 100%; padding: 6px; border: 1px solid var(--border-color); border-radius: 4px; font-family: inherit; font-size: 14px;">`;
                        }
                        row.classList.add('editing');
                        this.innerHTML = '<i class="icon">💾</i>';
                        this.style.color = 'var(--green)';
                    }
                });
            });

            // Delete functionality
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
                        const row = this.closest('tr');
                        row.remove();
                    }
                });
            });
        });
    </script>
</body>
</html>
