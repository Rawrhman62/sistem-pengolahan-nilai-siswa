<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - Kelas {{ $kelasNama }} - E-RAPOR</title>
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
            --green-light: #D1FAE5;
            --green-text: #065F46;
            --orange: #F59E0B;
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

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-hover));
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .toolbar-card {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
        }

        .toolbar-grid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: var(--primary-blue);
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--bg-light);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .filter-label-text {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-gray);
            text-transform: uppercase;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--text-dark);
            cursor: pointer;
        }

        .radio-option input {
            cursor: pointer;
            accent-color: var(--primary-blue);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
        }

        .btn-submit-all {
            background-color: var(--primary-blue);
            color: white;
        }

        .btn-submit-all:hover {
            background-color: var(--primary-blue-hover);
        }

        .btn-reset {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-reset:hover {
            background-color: #E2E8F0;
        }

        .table-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .table-responsive {
            width: 100%;
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

        .sortable-header {
            color: var(--text-gray);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .sortable-header:hover {
            color: var(--primary-blue);
        }

        .sort-icon {
            font-size: 11px;
            opacity: 0.7;
        }

        .data-table td {
            font-size: 14px;
            color: var(--text-dark);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .input-nilai {
            width: 85px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-nilai:focus {
            border-color: var(--primary-blue);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.saved {
            background-color: var(--green-light);
            color: var(--green-text);
        }

        .status-badge.pending {
            background-color: #FEF3C7;
            color: #92400E;
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

        .empty-state {
            padding: 40px;
            text-align: center;
            color: var(--text-gray);
        }

        .pagination-container {
            padding: 20px 24px;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('guru.dashboard') }}" class="back-link">← Kembali ke Daftar Kelas</a>
        
        <div class="header">
            <h1>Input Nilai Kelas {{ $kelasNama }}</h1>
            <p>Menampilkan data siswa aktif. Gunakan panel filter untuk mempercepat pencarian data.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

            <div class="toolbar-card">
                <div class="toolbar-grid">
                    <div class="search-box">
                        <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari nama atau NIS siswa...">
                    </div>
                    
                    <div class="filter-group">
                        <span class="filter-label-text">Filter Nilai:</span>
                        <label class="radio-option">
                            <input type="radio" name="filter_status" value="all" {{ request('filter_status', 'all') == 'all' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"> Semua
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="filter_status" value="sudah" {{ request('filter_status') == 'sudah' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"> Sudah Dinilai
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="filter_status" value="belum" {{ request('filter_status') == 'belum' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"> Belum Dinilai
                        </label>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn-action btn-submit-all" style="background-color: #4B5563;">Terapkan</button>
                        @if(request('search') || (request('filter_status') && request('filter_status') != 'all'))
                            <a href="{{ url()->current() }}" class="btn-action btn-reset">Reset</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <div class="table-card">
            <form method="POST" action="{{ route('guru.simpanNilai') }}">
                @csrf
                <input type="hidden" name="id_class" value="{{ $id_class }}">

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'nis', 'direction' => (request('sort') == 'nis' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="sortable-header">
                                        NIS 
                                        <span class="sort-icon">{{ request('sort') == 'nis' ? (request('direction') == 'asc' ? '▲' : '▼') : '↕' }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'name', 'direction' => (request('sort', 'name') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="sortable-header">
                                        Nama Siswa 
                                        <span class="sort-icon">{{ request('sort', 'name') == 'name' ? (request('direction') == 'asc' ? '▲' : '▼') : '↕' }}</span>
                                    </a>
                                </th>
                                <th style="width: 160px;">Nilai Pengetahuan</th>
                                <th style="width: 160px;">Nilai Keterampilan</th>
                                <th>Rata-rata</th>
                                <th>Predikat</th>
                                <th style="text-align: right; width: 140px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $index => $userSiswa)
                                @php
                                    $nilai = $nilaiList->get($userSiswa->id_user);
                                    $pengetahuan = $nilai ? $nilai->nilai_pengetahuan : null;
                                    $keterampilan = $nilai ? $nilai->nilai_keterampilan : null;
                                    
                                    $hasGrades = !is_null($pengetahuan) || !is_null($keterampilan);
                                    $rerata = $hasGrades ? round((($pengetahuan ?? 0) + ($keterampilan ?? 0)) / 2, 1) : '-';
                                    
                                    // Match grade range rules standard
                                    $predikat = '-';
                                    if($hasGrades) {
                                        if($rerata >= 85) $predikat = 'A';
                                        elseif($rerata >= 75) $predikat = 'B';
                                        elseif($rerata >= 65) $predikat = 'C';
                                        else $predikat = 'D';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $siswaList->firstItem() + $index }}</td>
                                    <td><code>{{ $userSiswa->student->nis ?? '-' }}</code></td>
                                    <td><strong>{{ $userSiswa->name }}</strong></td>
                                    <td>
                                        <input type="number" name="nilai[{{ $userSiswa->id_user }}][pengetahuan]" 
                                               class="input-nilai" min="0" max="100" placeholder="0-100" 
                                               value="{{ $pengetahuan }}">
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $userSiswa->id_user }}][keterampilan]" 
                                               class="input-nilai" min="0" max="100" placeholder="0-100" 
                                               value="{{ $keterampilan }}">
                                    </td>
                                    <td><strong>{{ $rerata }}</strong></td>
                                    <td><span style="font-weight: 600;">{{ $predikat }}</span></td>
                                    <td style="text-align: right;">
                                        @if($hasGrades)
                                            <span class="status-badge saved">✓ Tersimpan</span>
                                        @else
                                            <span class="status-badge pending">Belum Diisi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;">👥</div>
                                            <p>Tidak ada data siswa ditemukan yang sesuai dengan kriteria pencarian Anda.</p>
                                        </div>
                                    </td>
                                endtr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($siswaList->count() > 0)
                    <div style="padding: 20px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-action btn-submit-all">Simpan Semua Nilai</button>
                    </div>
                @endif
            </form>

            @if($siswaList->hasPages())
                <div class="pagination-container">
                    {{ $siswaList->appends(request()->all())->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>