<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Data Kelas - E-RAPOR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('admin._master-data-styles')
</head>
<body>
<div class="app-container">
    @include('admin._sidebar', ['active' => 'data'])

    <main class="main-content">
        @include('admin._topbar')

        <div class="content-wrapper">
            @include('admin._alerts')

            <div class="page-header">
                <div class="page-title">
                    <h1>Master Data</h1>
                    <p>Kelola data referensi sekolah secara menyeluruh.</p>
                </div>
                <div class="action-buttons">
                    <x-excel-buttons export-route="admin.kelas.export" import-route="admin.kelas.import" template-route="admin.kelas.template" />
                </div>
            </div>

            <div class="tabs">
                <a href="{{ route('admin.siswa') }}" class="tab-item">Siswa</a>
                <a href="{{ route('admin.guru') }}" class="tab-item">Guru</a>
                <a href="{{ route('admin.mapel') }}" class="tab-item">Mapel</a>
                <a href="{{ route('admin.kelas') }}" class="tab-item active">Kelas</a>
            </div>

            <div class="filter-bar">
                <form method="GET" action="{{ route('admin.kelas') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari nama kelas..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="btn btn-search">🔍 Cari</button>

                    @if(isset($gradeList) && $gradeList->count() > 0)
                    <select name="grade" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Tingkat</option>
                        @foreach($gradeList as $g)
                            <option value="{{ $g }}" {{ request('grade') == $g ? 'selected' : '' }}>Tingkat {{ $g }}</option>
                        @endforeach
                    </select>
                    @endif

                    @if(request()->hasAny(['search','grade']))
                    <a href="{{ route('admin.kelas') }}" class="btn btn-reset">Reset</a>
                    @endif
                </form>
                @if(request()->hasAny(['search','grade']))
                <p class="filter-info">Menampilkan {{ $kelas->total() }} hasil</p>
                @endif
            </div>

            <div class="excel-grid-wrapper">
                <div class="excel-hint">💡 Klik sel untuk mengedit langsung. Tekan Enter atau klik di luar untuk menyimpan.</div>
                <table class="excel-grid" id="kelasGrid">
                    <thead>
                        <tr>
                            <th class="row-num">No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                            <th>Jumlah Siswa</th>
                            <th class="col-action">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $index => $k)
                        <tr data-id="{{ $k->id_class }}" data-url="{{ route('admin.kelas.update', $k->id_class) }}">
                            <td class="row-num">{{ $kelas->firstItem() + $index }}</td>
                            <td class="editable" data-field="name">{{ $k->name }}</td>
                            <td class="editable" data-field="grade">{{ $k->grade ?? '' }}</td>
                            <td class="readonly">{{ $k->homeroom && $k->homeroom->user ? $k->homeroom->user->name : '-' }}</td>
                            <td class="readonly">{{ $k->students()->count() }}</td>
                            <td class="col-action">
                                <button class="btn-delete-row" data-url="{{ route('admin.kelas.delete', $k->id_class) }}" title="Hapus">🗑</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-row">Tidak ada data kelas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kelas->hasPages())
            <div class="pagination-container">{{ $kelas->links() }}</div>
            @endif
        </div>
    </main>
</div>
@include('admin._excel-grid-script')
</body>
</html>
