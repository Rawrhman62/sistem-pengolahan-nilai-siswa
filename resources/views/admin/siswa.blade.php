<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Data Siswa - E-RAPOR</title>
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

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h1>Master Data</h1>
                    <p>Kelola data referensi sekolah secara menyeluruh.</p>
                </div>
                <div class="action-buttons">
                    <x-excel-buttons export-route="admin.siswa.export" import-route="admin.siswa.import" template-route="admin.siswa.template" />
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <a href="{{ route('admin.siswa') }}" class="tab-item active">Siswa</a>
                <a href="{{ route('admin.guru') }}" class="tab-item">Guru</a>
                <a href="{{ route('admin.mapel') }}" class="tab-item">Mapel</a>
                <a href="{{ route('admin.kelas') }}" class="tab-item">Kelas</a>
            </div>

            <!-- Search and Filter -->
            <div class="filter-bar">
                <form method="GET" action="{{ route('admin.siswa') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari nama atau NIS..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="btn btn-search">🔍 Cari</button>

                    @if(isset($kelasList) && $kelasList->count() > 0)
                    <select name="id_class" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $id => $name)
                            <option value="{{ $id }}" {{ request('id_class') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @endif

                    @if(isset($entryYearList) && $entryYearList->count() > 0)
                    <select name="entry_year" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun Masuk</option>
                        @foreach($entryYearList as $yr)
                            <option value="{{ $yr }}" {{ request('entry_year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                    @endif

                    @if(request()->hasAny(['search','id_class','entry_year']))
                    <a href="{{ route('admin.siswa') }}" class="btn btn-reset">Reset</a>
                    @endif
                </form>
                @if(request()->hasAny(['search','id_class','entry_year']))
                <p class="filter-info">Menampilkan {{ $siswa->total() }} hasil</p>
                @endif
            </div>

            <!-- Excel-like Grid -->
            <div class="excel-grid-wrapper">
                <div class="excel-hint">💡 Klik sel untuk mengedit langsung. Tekan Enter atau klik di luar untuk menyimpan.</div>
                <table class="excel-grid" id="siswaGrid">
                    <thead>
                        <tr>
                            <th class="row-num">#</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Tahun Masuk</th>
                            <th>Jenis Kelamin</th>
                            <th class="col-action">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $index => $s)
                        <tr data-id="{{ $s->id_user }}" data-url="{{ route('admin.siswa.update', $s->id_user) }}">
                            <td class="row-num">{{ $siswa->firstItem() + $index }}</td>
                            <td class="editable" data-field="name">{{ $s->user->name ?? '' }}</td>
                            <td class="editable" data-field="nis">{{ $s->nis }}</td>
                            <td class="editable" data-field="nisn">{{ $s->nisn ?? '' }}</td>
                            <td class="editable" data-field="class_name">{{ $s->classRoom->name ?? '' }}</td>
                            <td class="editable" data-field="entry_year">{{ $s->entry_year ?? '' }}</td>
                            <td class="editable" data-field="gender" data-type="select" data-options="M:Laki-laki,F:Perempuan">{{ $s->user->gender === 'M' ? 'Laki-laki' : ($s->user->gender === 'F' ? 'Perempuan' : '') }}</td>
                            <td class="col-action">
                                <button class="btn-delete-row" data-url="{{ route('admin.siswa.delete', $s->id_user) }}" title="Hapus">🗑</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="empty-row">Tidak ada data siswa</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($siswa->hasPages())
            <div class="pagination-container">{{ $siswa->links() }}</div>
            @endif
        </div>
    </main>
</div>
@include('admin._excel-grid-script')
</body>
</html>
