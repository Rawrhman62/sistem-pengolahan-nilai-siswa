<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - E-RAPOR</title>
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
            --danger: #EF4444;
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
            background-color: var(--bg-light);
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
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .btn-add:hover {
            background-color: var(--btn-purple-hover);
        }

        /* Card Container */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            padding: 10px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            width: 300px;
            transition: border-color 0.2s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--btn-purple);
        }
        
        .btn-search {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
        }
        
        .btn-search:hover {
            background-color: var(--primary-blue-hover);
        }
        
        .btn-clear {
            background-color: var(--gray-light);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
            display: flex;
            align-items: center;
        }
        
        .btn-clear:hover {
            background-color: #E2E8F0;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
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
            font-weight: 600;
            color: var(--text-gray);
            font-size: 13px;
            background-color: #fafbfc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            font-size: 14px;
            color: var(--text-dark);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .badge-role {
            background-color: #E0E7FF;
            color: #4338CA;
        }

        .badge-success {
            background-color: var(--green-light);
            color: var(--green-text);
        }
        
        .badge-warning {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .user-meta {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .user-id {
            font-size: 12px;
            color: var(--text-gray);
            margin-top: 2px;
        }

        /* Pagination */
        .pagination-container {
            padding: 20px 24px;
            border-top: 1px solid var(--border-color);
        }

        /* Simple overrides for Laravel paginator */
        nav .relative.z-0.inline-flex {
            box-shadow: none;
        }
        
        .empty-state {
            padding: 40px;
            text-align: center;
            color: var(--text-gray);
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
                <li class="menu-item active">
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
                        <h1>Manajemen User</h1>
                        <p>Kelola daftar pengguna, peran, dan akses sistem.</p>
                    </div>
                    <a href="{{ route('admin.register') }}" class="btn-add">
                        <span style="font-size: 16px; margin-right: 4px;">+</span> Tambah Pengguna Baru
                    </a>
                </div>
                
                <!-- Card Container -->
                <div class="card">
                    <div class="card-header">
                        <h3 style="font-size: 16px; color: var(--text-dark); font-weight: 600;">Daftar Pengguna</h3>
                        
                        <form method="GET" action="{{ route('admin.manage') }}" class="search-form">
                            <input type="text" id="search" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari nama, ID, atau email...">
                            <button type="submit" class="btn-search">Cari</button>
                            @if(request('search'))
                                <a href="{{ route('admin.manage') }}" class="btn-clear">Reset</a>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Username</th>
                                    <th>Kontak</th>
                                    <th>Peran (Role)</th>
                                    <th>Status Password</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="user-meta">
                                                <span class="user-name">{{ $user->name }}</span>
                                                <span class="user-id">ID: {{ $user->user_id }}</span>
                                            </div>
                                        </td>
                                        <td><strong>{{ $user->user_name }}</strong></td>
                                        <td>
                                            <div class="user-meta">
                                                <span>{{ $user->email }}</span>
                                                @if($user->phone_number)
                                                    <span class="user-id">{{ $user->phone_number }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-role">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td>
                                            @if($user->password_set)
                                                <span class="badge badge-success">Sudah Diset</span>
                                            @else
                                                <span class="badge badge-warning">Belum Diset</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;">👥</div>
                                                <p>Tidak ada pengguna yang ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($users->hasPages())
                        <div class="pagination-container">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>