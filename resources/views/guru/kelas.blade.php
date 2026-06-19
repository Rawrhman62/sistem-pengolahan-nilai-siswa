<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - Kelas {{ $kelasNama }} - E-RAPOR</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            --red: #EF4444;
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
            max-width: 1600px;
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
            max-width: 400px;
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

        .controls-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .select-control {
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: var(--white);
            cursor: pointer;
            outline: none;
        }

        .select-control:focus {
            border-color: var(--primary-blue);
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-blue-hover);
        }

        .btn-secondary {
            background-color: #6B7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4B5563;
        }

        .btn-reset {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
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
            min-width: 1200px;
        }

        .data-table th, .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            font-weight: 600;
            color: var(--text-gray);
            font-size: 12px;
            background-color: #fafbfc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
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
            width: 70px;
            padding: 6px 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 13px;
            text-align: center;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-nilai:focus {
            border-color: var(--primary-blue);
        }

        .input-nilai:disabled {
            background-color: #F3F4F6;
            cursor: not-allowed;
        }

        .column-toggle {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--text-gray);
            cursor: pointer;
        }

        .column-toggle input[type="checkbox"] {
            cursor: pointer;
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

        .grade-type-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0;
        }

        .tab-button {
            padding: 12px 24px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-gray);
            transition: all 0.2s;
            margin-bottom: -2px;
        }

        .tab-button:hover {
            color: var(--primary-blue);
        }

        .tab-button.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
        }

        .info-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #EFF6FF;
            color: #1E40AF;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('guru.index') }}" class="back-link">← Kembali ke Daftar Kelas</a>
        
        <div class="header">
            <h1>Input Nilai Kelas {{ $kelasNama }}</h1>
            <p>Kelola semua jenis nilai siswa untuk semester aktif. Pilih jenis nilai yang ingin diinput.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Toolbar with Search, Semester, and Grade Type Selection -->
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

            <div class="toolbar-card">
                <div class="toolbar-grid">
                    <div class="search-box">
                        <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari nama atau NIS siswa...">
                    </div>
                    
                    <div class="controls-group">
                        <select name="semester" class="select-control" onchange="this.form.submit()">
                            <option value="1/{{ substr(date('Y'), -2) }}" {{ $semester == '1/' . substr(date('Y'), -2) ? 'selected' : '' }}>Semester 1/{{ substr(date('Y'), -2) }}</option>
                            <option value="2/{{ substr(date('Y'), -2) }}" {{ $semester == '2/' . substr(date('Y'), -2) ? 'selected' : '' }}>Semester 2/{{ substr(date('Y'), -2) }}</option>
                            <option value="1/{{ substr(date('Y')+1, -2) }}" {{ $semester == '1/' . substr(date('Y')+1, -2) ? 'selected' : '' }}>Semester 1/{{ substr(date('Y')+1, -2) }}</option>
                            <option value="2/{{ substr(date('Y')+1, -2) }}" {{ $semester == '2/' . substr(date('Y')+1, -2) ? 'selected' : '' }}>Semester 2/{{ substr(date('Y')+1, -2) }}</option>
                        </select>

                        <select name="grade_type" class="select-control" onchange="this.form.submit()">
                            <option value="harian" {{ $gradeType == 'harian' ? 'selected' : '' }}>Nilai Harian</option>
                            <option value="keterampilan" {{ $gradeType == 'keterampilan' ? 'selected' : '' }}>Nilai Keterampilan</option>
                            <option value="ulangan" {{ $gradeType == 'ulangan' ? 'selected' : '' }}>Nilai Ulangan</option>
                            <option value="ujian" {{ $gradeType == 'ujian' ? 'selected' : '' }}>Nilai Ujian</option>
                        </select>

                        <button type="submit" class="btn-action btn-secondary">Terapkan</button>
                        @if(request('search'))
                            <a href="{{ url()->current() }}?semester={{ $semester }}&grade_type={{ $gradeType }}" class="btn-action btn-reset">Reset</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <!-- Table Card -->
        <div class="table-card">
            <form method="POST" action="{{ route('guru.simpanNilai') }}" id="nilaiForm">
                @csrf
                <input type="hidden" name="id_class" value="{{ $id_class }}">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <input type="hidden" name="grade_type" value="{{ $gradeType }}">

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 100px;">NIS</th>
                                <th style="width: 200px;">Nama Siswa</th>

                                @if($gradeType == 'harian')
                                    @for($i = 1; $i <= 12; $i++)
                                        <th style="width: 80px; text-align: center;">
                                            <div style="margin-bottom: 4px;">Minggu {{ $i }}</div>
                                            <label class="column-toggle">
                                                <input type="checkbox" 
                                                       class="column-toggle-checkbox" 
                                                       data-column="minggu_{{ $i }}"
                                                       {{ in_array('minggu_' . $i, $disabledColumns) ? '' : 'checked' }}
                                                       onchange="toggleColumn('minggu_{{ $i }}', this.checked)">
                                                <span>Aktif</span>
                                            </label>
                                        </th>
                                    @endfor
                                @elseif($gradeType == 'keterampilan')
                                    <th style="width: 120px; text-align: center;">Nilai Keterampilan</th>
                                @elseif($gradeType == 'ulangan')
                                    @for($i = 1; $i <= 8; $i++)
                                        <th style="width: 90px; text-align: center;">Ulangan {{ $i }}</th>
                                    @endfor
                                @elseif($gradeType == 'ujian')
                                    <th style="width: 100px; text-align: center;">Awal Ganjil</th>
                                    <th style="width: 100px; text-align: center;">Akhir Ganjil</th>
                                    <th style="width: 100px; text-align: center;">Awal Genap</th>
                                    <th style="width: 100px; text-align: center;">Akhir Genap</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $index => $userSiswa)
                                @php
                                    $nilai = $nilaiList->get($userSiswa->id_user);
                                @endphp
                                <tr>
                                    <td>{{ $siswaList->firstItem() + $index }}</td>
                                    <td><code>{{ $userSiswa->student->nis ?? '-' }}</code></td>
                                    <td><strong>{{ $userSiswa->name }}</strong></td>

                                    @if($gradeType == 'harian')
                                        @for($i = 1; $i <= 12; $i++)
                                            @php
                                                $minggu = 'minggu_' . $i;
                                                $nilaiHarian = $nilai?->nilaiHarian;
                                                $isDisabled = in_array($minggu, $disabledColumns);
                                            @endphp
                                            <td style="text-align: center;" data-column="{{ $minggu }}">
                                                <input type="number" 
                                                       name="nilai[{{ $userSiswa->id_user }}][{{ $minggu }}]" 
                                                       class="input-nilai" 
                                                       min="0" 
                                                       max="100" 
                                                       placeholder="-" 
                                                       value="{{ $isDisabled ? '' : ($nilaiHarian?->$minggu ?? '') }}"
                                                       {{ $isDisabled ? 'disabled' : '' }}>
                                            </td>
                                        @endfor
                                    @elseif($gradeType == 'keterampilan')
                                        @php
                                            $nilaiKeterampilan = $nilai?->nilaiKeterampilan;
                                        @endphp
                                        <td style="text-align: center;">
                                            <input type="number" 
                                                   name="nilai[{{ $userSiswa->id_user }}][nilai]" 
                                                   class="input-nilai" 
                                                   min="0" 
                                                   max="100" 
                                                   placeholder="0-100" 
                                                   value="{{ $nilaiKeterampilan?->nilai ?? '' }}">
                                        </td>
                                    @elseif($gradeType == 'ulangan')
                                        @for($i = 1; $i <= 8; $i++)
                                            @php
                                                $ulangan = 'ulangan_' . $i;
                                                $nilaiUlangan = $nilai?->nilaiUlangan;
                                            @endphp
                                            <td style="text-align: center;">
                                                <input type="number" 
                                                       name="nilai[{{ $userSiswa->id_user }}][{{ $ulangan }}]" 
                                                       class="input-nilai" 
                                                       min="0" 
                                                       max="100" 
                                                       placeholder="-" 
                                                       value="{{ $nilaiUlangan?->$ulangan ?? '' }}">
                                            </td>
                                        @endfor
                                    @elseif($gradeType == 'ujian')
                                        @php
                                            $nilaiUjian = $nilai?->nilaiUjian;
                                        @endphp
                                        <td style="text-align: center;">
                                            <input type="number" 
                                                   name="nilai[{{ $userSiswa->id_user }}][awal_ganjil]" 
                                                   class="input-nilai" 
                                                   min="0" 
                                                   max="100" 
                                                   placeholder="-" 
                                                   value="{{ $nilaiUjian?->awal_ganjil ?? '' }}">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" 
                                                   name="nilai[{{ $userSiswa->id_user }}][akhir_ganjil]" 
                                                   class="input-nilai" 
                                                   min="0" 
                                                   max="100" 
                                                   placeholder="-" 
                                                   value="{{ $nilaiUjian?->akhir_ganjil ?? '' }}">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" 
                                                   name="nilai[{{ $userSiswa->id_user }}][awal_genap]" 
                                                   class="input-nilai" 
                                                   min="0" 
                                                   max="100" 
                                                   placeholder="-" 
                                                   value="{{ $nilaiUjian?->awal_genap ?? '' }}">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" 
                                                   name="nilai[{{ $userSiswa->id_user }}][akhir_genap]" 
                                                   class="input-nilai" 
                                                   min="0" 
                                                   max="100" 
                                                   placeholder="-" 
                                                   value="{{ $nilaiUjian?->akhir_genap ?? '' }}">
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20">
                                        <div class="empty-state">
                                            <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;">👥</div>
                                            <p>Tidak ada data siswa ditemukan yang sesuai dengan kriteria pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($siswaList->count() > 0)
                    <div style="padding: 20px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <div class="info-badge">
                            📝 Semester: {{ $semester }} | Jenis: 
                            @if($gradeType == 'harian') Nilai Harian (12 Minggu)
                            @elseif($gradeType == 'keterampilan') Nilai Keterampilan
                            @elseif($gradeType == 'ulangan') Nilai Ulangan
                            @elseif($gradeType == 'ujian') Nilai Ujian
                            @endif
                        </div>
                        <button type="submit" class="btn-action btn-primary">Simpan Semua Nilai</button>
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

    <script>
        function toggleColumn(column, enabled) {
            fetch('{{ route("guru.toggleColumn") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id_class: '{{ $id_class }}',
                    column: column,
                    enabled: enabled
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Disable/enable all inputs in this column
                    document.querySelectorAll(`td[data-column="${column}"] input`).forEach(input => {
                        input.disabled = !enabled;
                        if (!enabled) {
                            input.value = '';
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
