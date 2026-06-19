<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tahun Ajaran - E-RAPOR</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Sidebar Styles */
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

        /* Settings Card */
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
            background: linear-gradient(135deg, #1a2942 0%, #2a3f5f 100%);
            border-radius: 12px;
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .banner-content {
            position: relative;
            z-index: 2;
        }

        .banner-header {
            display: inline-block;
            background-color: rgba(99, 102, 241, 0.3);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .banner-title {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .banner-title h2 {
            font-size: 36px;
            font-weight: 700;
            margin: 0;
        }

        .semester-badge {
            background-color: var(--btn-purple);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .banner-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.85);
            max-width: 600px;
            line-height: 1.6;
        }

        .banner-icon {
            font-size: 140px;
            opacity: 0.06;
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            line-height: 1;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: var(--green-light);
            color: var(--green-text);
            border: 1px solid #34d399;
        }

        .info-box {
            background-color: #EFF6FF;
            border-left: 4px solid #3B82F6;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .info-box h3 {
            font-size: 14px;
            font-weight: 600;
            color: #1E40AF;
            margin-bottom: 8px;
        }

        .info-box p {
            font-size: 13px;
            color: #1E3A8A;
            line-height: 1.6;
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
            font-weight: 600;
            color: var(--text-gray);
            font-size: 13px;
            background-color: #fafbfc;
            text-transform: uppercase;
        }

        .data-table td {
            font-size: 14px;
            color: var(--text-dark);
            vertical-align: middle;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-aktif {
            background-color: var(--green-light);
            color: var(--green-text);
        }

        .status-upcoming {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-arsip {
            background-color: var(--gray-light);
            color: var(--gray-text);
        }

        /* Actions */
        .btn-edit {
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

        .btn-edit:hover {
            background-color: var(--btn-purple-hover);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--btn-purple);
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn-cancel {
            padding: 10px 20px;
            background-color: var(--gray-light);
            color: var(--text-dark);
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-save {
            padding: 10px 20px;
            background-color: var(--btn-purple);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-save:hover {
            background-color: var(--btn-purple-hover);
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
                <li class="menu-item">
                    <a href="{{ route('admin.excelconfig') }}">
                        <i class="icon">📊</i> Konfigurasi Excel
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
                    <div class="user-profile">
                        <div class="user-info">
                            <strong>{{ auth()->user()->name ?? 'Admin Sekolah' }}</strong>
                            <span>ADMINISTRATOR</span>
                        </div>
                        <div class="avatar">
                            {{ substr(auth()->user()->name ?? 'AS', 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <h1>Kelola Tahun Ajaran</h1>
                        <p>Sistem otomatis menentukan tahun ajaran aktif berdasarkan tanggal saat ini.</p>
                    </div>
                    <button class="btn-add" onclick="openAddModal()">
                        <span style="font-size: 16px; margin-right: 4px;">+</span> Tambah Tahun Ajaran
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <!-- Info Box -->
                <div class="info-box">
                    <h3>ℹ️ Sistem Otomatis</h3>
                    <p>Tahun ajaran aktif ditentukan secara otomatis berdasarkan tanggal hari ini. Sistem akan beralih ke tahun ajaran berikutnya setelah tanggal selesai terlewati. Anda dapat mengatur tanggal selesai untuk setiap tahun ajaran.</p>
                </div>
                
                <!-- Content Area -->
                <div class="settings-card">
                    <!-- Active Banner -->
                    @if($currentTahunAjaran)
                        <div class="active-banner">
                            <div class="banner-content">
                                <div class="banner-header">
                                    📍 TAHUN AJARAN AKTIF SAAT INI
                                </div>
                                <div class="banner-title">
                                    <h2>{{ $currentTahunAjaran->tahun_ajaran }}</h2>
                                    <span class="semester-badge">
                                        Semester {{ $currentSemester == 1 ? 'Ganjil' : 'Genap' }}
                                    </span>
                                </div>
                                <div class="banner-desc">
                                    Periode: {{ $currentTahunAjaran->tanggal_mulai->format('d F Y') }} - {{ $currentTahunAjaran->tanggal_selesai->format('d F Y') }}<br>
                                    Seluruh data rapor, leger, dan input nilai saat ini tersimpan pada periode ini.
                                </div>
                            </div>
                            <div class="banner-icon">📅</div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            ⚠️ Tidak ada tahun ajaran aktif saat ini. Silakan tambahkan tahun ajaran baru.
                        </div>
                    @endif

                    <!-- Table -->
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tahun Pelajaran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tahunAjaranList as $tahun)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $isActive = $today->between($tahun->tanggal_mulai, $tahun->tanggal_selesai);
                                    $isPast = $today->greaterThan($tahun->tanggal_selesai);
                                    $isFuture = $today->lessThan($tahun->tanggal_mulai);
                                @endphp
                                <tr>
                                    <td><strong>{{ $tahun->tahun_ajaran }}</strong></td>
                                    <td>{{ $tahun->tanggal_mulai->format('d M Y') }}</td>
                                    <td>{{ $tahun->tanggal_selesai->format('d M Y') }}</td>
                                    <td>
                                        @if($isActive)
                                            <span class="status-badge status-aktif">
                                                <span style="font-size: 10px;">●</span> AKTIF
                                            </span>
                                        @elseif($isFuture)
                                            <span class="status-badge status-upcoming">
                                                Akan Datang
                                            </span>
                                        @else
                                            <span class="status-badge status-arsip">Arsip</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <button class="btn-edit" onclick="openEditModal({{ $tahun->id }}, '{{ $tahun->tahun_ajaran }}', '{{ $tahun->tanggal_mulai->format('Y-m-d') }}', '{{ $tahun->tanggal_selesai->format('Y-m-d') }}')">
                                            ✎ Edit Tanggal
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-gray);">
                                        <div style="font-size: 40px; margin-bottom: 10px;">📅</div>
                                        <p>Belum ada data tahun ajaran. Silakan tambahkan tahun ajaran baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h2 class="modal-header">Edit Tanggal Tahun Ajaran</h2>
            <form method="POST" action="{{ route('admin.tahun-ajaran.update') }}">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <input type="text" id="edit_tahun" readonly style="background-color: var(--gray-light);">
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" readonly style="background-color: var(--gray-light);">
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <h2 class="modal-header">Tambah Tahun Ajaran Baru</h2>
            <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}">
                @csrf
                
                <div class="form-group">
                    <label>Tahun Ajaran (contoh: 2026/2027)</label>
                    <input type="text" name="tahun_ajaran" placeholder="2026/2027" required pattern="[0-9]{4}/[0-9]{4}">
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn-save">Tambah Tahun Ajaran</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, tahun, tanggalMulai, tanggalSelesai) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_tahun').value = tahun;
            document.getElementById('edit_tanggal_mulai').value = tanggalMulai;
            document.getElementById('edit_tanggal_selesai').value = tanggalSelesai;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const addModal = document.getElementById('addModal');
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == addModal) {
                closeAddModal();
            }
        }
    </script>
</body>
</html>
