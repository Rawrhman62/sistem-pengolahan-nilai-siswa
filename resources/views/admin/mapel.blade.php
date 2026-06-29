<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Data Mapel - E-RAPOR</title>
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
                    <x-excel-buttons export-route="admin.mapel.export" import-route="admin.mapel.import" template-route="admin.mapel.template" />
                </div>
            </div>

            <div class="tabs">
                <a href="{{ route('admin.siswa') }}" class="tab-item">Siswa</a>
                <a href="{{ route('admin.guru') }}" class="tab-item">Guru</a>
                <a href="{{ route('admin.mapel') }}" class="tab-item active">Mapel</a>
                <a href="{{ route('admin.kelas') }}" class="tab-item">Kelas</a>
            </div>

            <div class="filter-bar">
                <form method="GET" action="{{ route('admin.mapel') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari kode atau nama mapel..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="btn btn-search">🔍 Cari</button>

                    @if(isset($groupList) && $groupList->count() > 0)
                    <select name="group" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Kelompok</option>
                        @foreach($groupList as $grp)
                            <option value="{{ $grp }}" {{ request('group') == $grp ? 'selected' : '' }}>{{ $grp }}</option>
                        @endforeach
                    </select>
                    @endif

                    @if(request()->hasAny(['search','group']))
                    <a href="{{ route('admin.mapel') }}" class="btn btn-reset">Reset</a>
                    @endif
                </form>
                @if(request()->hasAny(['search','group']))
                <p class="filter-info">Menampilkan {{ $mapel->total() }} hasil</p>
                @endif
            </div>

            <div class="excel-grid-wrapper">
                <div class="excel-hint">💡 Klik sel untuk mengedit langsung. Tekan Enter atau klik di luar untuk menyimpan.</div>
                <table class="excel-grid" id="mapelGrid">
                    <thead>
                        <tr>
                            <th class="row-num">No</th>
                            <th>Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Tingkat</th>
                            <th>Kurikulum</th>
                            <th>Kelompok</th>
                            <th class="col-action">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapel as $index => $m)
                        <tr data-id="{{ $m->id_subjects }}" data-url="{{ route('admin.mapel.update', $m->id_subjects) }}">
                            <td class="row-num">{{ $mapel->firstItem() + $index }}</td>
                            <td class="editable" data-field="code">{{ $m->code ?? '' }}</td>
                            <td class="editable" data-field="name">{{ $m->name }}</td>
                            <td class="editable" data-field="grade">{{ $m->grade ?? '' }}</td>
                            <td class="editable" data-field="curriculum">{{ $m->curriculum ?? '' }}</td>
                            <td class="editable" data-field="group">{{ $m->group ?? '' }}</td>
                            <td class="col-action">
                                <button class="btn-delete-row" data-url="{{ route('admin.mapel.delete', $m->id_subjects) }}" title="Hapus">🗑</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="empty-row">Tidak ada data mata pelajaran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($mapel->hasPages())
            <div class="pagination-container">{{ $mapel->links() }}</div>
            @endif
        </div>
    </main>
</div>
@include('admin._excel-grid-script')
</body>
</html>
