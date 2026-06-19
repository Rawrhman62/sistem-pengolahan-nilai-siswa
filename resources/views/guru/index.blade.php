<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - E-RAPOR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0A2E5C;
            --primary-blue-hover: #15407D;
            --bg-light: #F4F7F6;
            --text-dark: #333333;
            --text-gray: #666666;
            --white: #FFFFFF;
            --border-color: #E2E8F0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Navbar Header */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white);
            padding: 16px 32px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-blue);
            text-decoration: none;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-gray);
        }

        .btn-logout {
            padding: 8px 16px;
            background-color: #EF4444;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }

        /* Hero banner summary section */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-hover));
            color: white;
            padding: 36px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .welcome-banner h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .welcome-banner p {
            opacity: 0.9;
            font-size: 15px;
        }

        /* Metric Summary Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background-color: var(--white);
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid var(--primary-blue);
        }

        .stat-card.card-siswa { border-left-color: var(--blue); }
        .stat-card.card-done { border-left-color: var(--green); }
        .stat-card.card-pending { border-left-color: var(--orange); }

        .stat-info .stat-label {
            font-size: 13px;
            color: var(--text-gray);
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .stat-info .stat-count {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .stat-icon {
            font-size: 36px;
            opacity: 0.3;
        }

        /* Search Filter Controls Bar style */
        .controls-card {
            background-color: var(--white);
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }

        .search-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-group {
            flex: 1;
            min-width: 260px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
        }

        .search-input:focus {
            border-color: var(--primary-blue);
        }

        .filter-select {
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background-color: var(--white);
            outline: none;
            min-width: 160px;
            cursor: pointer;
        }

        .btn-search {
            padding: 12px 24px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-search:hover {
            background-color: var(--primary-blue-hover);
        }

        .btn-reset {
            padding: 12px 16px;
            background-color: #E2E8F0;
            color: var(--text-dark);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
        }

        /* Table Data List Style */
        .table-card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #FAFBFB;
            padding: 16px 24px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-gray);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 18px 24px;
            font-size: 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #F8FAFC;
        }

        .btn-input {
            padding: 8px 16px;
            background-color: var(--primary-blue);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
            transition: background 0.2s;
        }

        .btn-input:hover {
            background-color: var(--primary-blue-hover);
        }

        /* Pagination Links Styling */
        .pagination-box {
            padding: 20px 24px;
            background-color: #FAFBFB;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-gray);
        }

        .pagination-nav {
            display: flex;
            gap: 6px;
        }

        .page-link-btn {
            padding: 8px 14px;
            border: 1px solid var(--border-color);
            background-color: var(--white);
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .page-link-btn.active {
            background-color: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .page-link-btn.disabled {
            color: #A0AEC0;
            background-color: #EDF2F7;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="navbar">
            <a href="#" class="brand">E-RAPOR</a>
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Guru Pengajar</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        </header>

        <div class="welcome-banner">
            <h1>Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
            <p>Sistem E-Rapor siap digunakan. Silakan kelola pencapaian nilai kompetensi rombongan belajar Anda di bawah ini.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">Total Kelas Diajar</div>
                    <div class="stat-count">{{ $totalKelas }}</div>
                </div>
                <div class="stat-icon">🏫</div>
            </div>
            <div class="stat-card card-siswa">
                <div class="stat-info">
                    <div class="stat-label">Total Murid Diampu</div>
                    <div class="stat-count">{{ $totalSiswa }}</div>
                </div>
                <div class="stat-icon">👥</div>
            </div>
            <div class="stat-card card-done">
                <div class="stat-info">
                    <div class="stat-label">Nilai Telah Diisi</div>
                    <div class="stat-count">{{ $nilaiDiinput }}</div>
                </div>
                <div class="stat-icon">📝</div>
            </div>
            <div class="stat-card card-pending">
                <div class="stat-info">
                    <div class="stat-label">Nilai Belum Diisi</div>
                    <div class="stat-count">{{ $nilaiBelumDiinput }}</div>
                </div>
                <div class="stat-icon">⏳</div>
            </div>
        </div>

        <div class="controls-card">
            <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" class="search-input" placeholder="Cari nama kelas atau mata pelajaran..." value="{{ request('search') }}">
                </div>
                
                <select name="sort" class="filter-select">
                    <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Urutkan: Nama Kelas</option>
                    <option value="mapel" {{ request('sort') == 'mapel' ? 'selected' : '' }}>Urutkan: Mata Pelajaran</option>
                    <option value="jumlah_siswa" {{ request('sort') == 'jumlah_siswa' ? 'selected' : '' }}>Urutkan: Jumlah Siswa</option>
                </select>

                <select name="direction" class="filter-select">
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Meningkat (A-Z)</option>
                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                </select>

                <button type="submit" class="btn-search">Filter Data</button>
                @if(request('search') || request('sort') != 'nama' || request('direction') != 'asc')
                    <a href="{{ route('dashboard') }}" class="btn-reset">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Rombongan Belajar</th>
                            <th>Mata Pelajaran Diampu</th>
                            <th style="width: 200px;">Jumlah Siswa</th>
                            <th style="width: 180px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $startNumber = ($kelasList->currentPage() - 1) * $kelasList->perPage() + 1; 
                        @endphp
                        @forelse($kelasList as $index => $kelas)
                            <tr>
                                <td>{{ $startNumber + $index }}</td>
                                <td><strong>Kelas {{ $kelas['nama'] }}</strong></td>
                                <td>{{ $kelas['mapel'] }}</td>
                                <td><span style="font-weight: 600;">{{ $kelas['jumlah_siswa'] }}</span> Murid</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('guru.kelas', $kelas['id_class']) }}" class="btn-input">Input Nilai</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-gray);">
                                    <div style="font-size: 40px; margin-bottom: 10px;">📚</div>
                                    <p>Tidak ada jadwal kelas mengajar yang sesuai dengan pencarian Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kelasList->hasPages())
                <div class="pagination-box">
                    <div class="pagination-info">
                        Menampilkan {{ $kelasList->firstItem() ?? 0 }} sampai {{ $kelasList->lastItem() ?? 0 }} dari total {{ $kelasList->total() }} kelas
                    </div>
                    <div class="pagination-nav">
                        {{-- Button Previous --}}
                        @if($kelasList->onFirstPage())
                            <span class="page-link-btn disabled">‹ Sebelum</span>
                        @else
                            <a href="{{ $kelasList->previousPageUrl() }}" class="page-link-btn">‹ Sebelum</a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($kelasList->getUrlRange(1, $kelasList->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="page-link-btn {{ $page == $kelasList->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        {{-- Button Next --}}
                        @if($kelasList->hasMorePages())
                            <a href="{{ $kelasList->nextPageUrl() }}" class="page-link-btn">Berikut ›</a>
                        @else
                            <span class="page-link-btn disabled">Berikut ›</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>