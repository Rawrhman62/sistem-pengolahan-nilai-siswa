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
            --primary: #0A2E5C;
            --primary-hover: #15407D;
            --bg-light: #F4F7F6;
            --text-dark: #333;
            --text-gray: #666;
            --white: #fff;
            --border: #E2E8F0;
            --excel-header: #217346;
            --excel-header-text: #fff;
            --excel-hover: #f0f7ff;
            --green: #10B981;
            --green-light: #D1FAE5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body { background: var(--bg-light); color: var(--text-dark); }

        .container { max-width: 1600px; margin: 0 auto; padding: 30px; }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover { text-decoration: underline; }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .header h1 { font-size: 28px; margin-bottom: 5px; }
        .header p { opacity: 0.9; font-size: 14px; }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: var(--green-light);
            color: #065f46;
            border: 1px solid #34d399;
        }

        .toolbar-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }

        .toolbar-grid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .search-box { flex: 1; min-width: 300px; max-width: 400px; }

        .search-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 7px;
            font-size: 13px;
            outline: none;
        }

        .search-input:focus { border-color: var(--primary); }

        .controls-group { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        .select-control {
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 7px;
            font-size: 13px;
            background: var(--white);
            cursor: pointer;
        }

        .select-control:focus { border-color: var(--primary); }

        .btn-action {
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: #6B7280; color: white; }
        .btn-secondary:hover { background: #4B5563; }
        .btn-reset { background: #F3F4F6; border: 1px solid var(--border); color: var(--text-gray); }
        .btn-reset:hover { background: #E5E7EB; }

        /* Excel Grid Styles */
        .excel-grid-wrapper {
            border: 1px solid #c0c0c0;
            border-radius: 6px;
            overflow: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            background: var(--white);
        }

        .excel-grid {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 1200px;
        }

        .excel-grid thead tr {
            background: var(--excel-header);
            color: var(--excel-header-text);
        }

        .excel-grid th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
            border-right: 1px solid rgba(255,255,255,.2);
            user-select: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .excel-grid th.col-center { text-align: center; }

        .excel-grid td {
            padding: 0 8px;
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            height: 38px;
            vertical-align: middle;
        }

        .excel-grid td.row-num {
            background: #f5f5f5;
            color: #888;
            text-align: center;
            font-size: 11px;
            padding: 0 8px;
            width: 50px;
            border-right: 2px solid #c0c0c0;
        }

        .excel-grid td.col-center { text-align: center; padding: 0; }

        .excel-grid tr:hover td { background-color: var(--excel-hover); }
        .excel-grid tr:hover td.row-num { background: #e8e8e8; }

        .input-nilai {
            width: 70px;
            padding: 6px 8px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 13px;
            text-align: center;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-nilai:focus {
            border-color: var(--primary);
            background: #fff8d6;
        }

        .input-nilai:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }

        .column-toggle {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            color: rgba(255,255,255,.9);
            margin-top: 4px;
        }

        .column-toggle label {
            display: flex;
            align-items: center;
            gap: 3px;
            cursor: pointer;
            font-size: 10px;
        }

        .column-toggle input[type="checkbox"] {
            cursor: pointer;
            width: 12px;
            height: 12px;
        }

        .empty-state {
            padding: 60px 40px;
            text-align: center;
            color: var(--text-gray);
        }

        .empty-state div {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .info-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #EFF6FF;
            color: #1E40AF;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .table-footer {
            padding: 18px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafbfc;
        }

        .pagination-container {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            background: #fafbfc;
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
                            <option value="1/{{ substr(date('Y'), -2) }}" {{ $semester == '1/' . substr(date('Y'), -2) ? 'selected' : '' }}>Semester Awal Ganjil {{ date('Y') }}/{{ date('Y')+1 }}</option>
                            <option value="2/{{ substr(date('Y'), -2) }}" {{ $semester == '2/' . substr(date('Y'), -2) ? 'selected' : '' }}>Semester Akhir Ganjil {{ date('Y') }}/{{ date('Y')+1 }}</option>
                            <option value="3/{{ substr(date('Y'), -2) }}" {{ $semester == '3/' . substr(date('Y'), -2) ? 'selected' : '' }}>Semester Awal Genap {{ date('Y') }}/{{ date('Y')+1 }}</option>
                            <option value="4/{{ substr(date('Y'), -2) }}" {{ $semester == '4/' . substr(date('Y'), -2) ? 'selected' : '' }}>Semester Akhir Genap {{ date('Y') }}/{{ date('Y')+1 }}</option>
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

        <!-- Excel Grid Table -->
        <div class="excel-grid-wrapper">
            <form method="POST" action="{{ route('guru.simpanNilai') }}" id="nilaiForm">
                @csrf
                <input type="hidden" name="id_class" value="{{ $id_class }}">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <input type="hidden" name="grade_type" value="{{ $gradeType }}">

                <table class="excel-grid">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 100px;">NIS</th>
                            <th style="width: 200px;">Nama Siswa</th>

                            @if($gradeType == 'harian')
                                @for($i = 1; $i <= 12; $i++)
                                    <th class="col-center" style="width: 90px;">
                                        <div>Minggu {{ $i }}</div>
                                        <div class="column-toggle">
                                            <label>
                                                <input type="checkbox" 
                                                       class="column-toggle-checkbox" 
                                                       data-column="minggu_{{ $i }}"
                                                       {{ in_array('minggu_' . $i, $disabledColumns) ? '' : 'checked' }}
                                                       onchange="toggleColumn('minggu_{{ $i }}', this.checked)">
                                                <span>Aktif</span>
                                            </label>
                                        </div>
                                    </th>
                                @endfor
                            @elseif($gradeType == 'keterampilan')
                                <th class="col-center" style="width: 120px;">Nilai Keterampilan</th>
                            @elseif($gradeType == 'ulangan')
                                @for($i = 1; $i <= 8; $i++)
                                    <th class="col-center" style="width: 90px;">Ulangan {{ $i }}</th>
                                @endfor
                            @elseif($gradeType == 'ujian')
                                <th class="col-center" style="width: 100px;">Awal Ganjil</th>
                                <th class="col-center" style="width: 100px;">Akhir Ganjil</th>
                                <th class="col-center" style="width: 100px;">Awal Genap</th>
                                <th class="col-center" style="width: 100px;">Akhir Genap</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $index => $userSiswa)
                            @php
                                $nilai = $nilaiList->get($userSiswa->id_user);
                            @endphp
                            <tr>
                                <td class="row-num">{{ $siswaList->firstItem() + $index }}</td>
                                <td><code style="font-size: 12px;">{{ $userSiswa->student->nis ?? '-' }}</code></td>
                                <td><strong>{{ $userSiswa->name }}</strong></td>

                                @if($gradeType == 'harian')
                                    @for($i = 1; $i <= 12; $i++)
                                        @php
                                            $minggu = 'minggu_' . $i;
                                            $nilaiHarian = $nilai?->nilaiHarian;
                                            $isDisabled = in_array($minggu, $disabledColumns);
                                        @endphp
                                        <td class="col-center" data-column="{{ $minggu }}">
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
                                    <td class="col-center">
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
                                        <td class="col-center">
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
                                    <td class="col-center">
                                        <input type="number" 
                                               name="nilai[{{ $userSiswa->id_user }}][awal_ganjil]" 
                                               class="input-nilai" 
                                               min="0" 
                                               max="100" 
                                               placeholder="-" 
                                               value="{{ $nilaiUjian?->awal_ganjil ?? '' }}">
                                    </td>
                                    <td class="col-center">
                                        <input type="number" 
                                               name="nilai[{{ $userSiswa->id_user }}][akhir_ganjil]" 
                                               class="input-nilai" 
                                               min="0" 
                                               max="100" 
                                               placeholder="-" 
                                               value="{{ $nilaiUjian?->akhir_ganjil ?? '' }}">
                                    </td>
                                    <td class="col-center">
                                        <input type="number" 
                                               name="nilai[{{ $userSiswa->id_user }}][awal_genap]" 
                                               class="input-nilai" 
                                               min="0" 
                                               max="100" 
                                               placeholder="-" 
                                               value="{{ $nilaiUjian?->awal_genap ?? '' }}">
                                    </td>
                                    <td class="col-center">
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
                                        <div>👥</div>
                                        <p>Tidak ada data siswa ditemukan yang sesuai dengan kriteria pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($siswaList->count() > 0)
                    <div class="table-footer">
                        <div class="info-badge">
                            📝 Semester: {{ $semester }} | Jenis: 
                            @if($gradeType == 'harian') Nilai Harian (12 Minggu)
                            @elseif($gradeType == 'keterampilan') Nilai Keterampilan
                            @elseif($gradeType == 'ulangan') Nilai Ulangan
                            @elseif($gradeType == 'ujian') Nilai Ujian
                            @endif
                        </div>
                        <button type="submit" class="btn-action btn-primary">💾 Simpan Semua Nilai</button>
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
