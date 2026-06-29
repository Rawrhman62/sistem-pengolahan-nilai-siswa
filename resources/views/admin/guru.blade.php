<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Data Guru - E-RAPOR</title>
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
                    <x-excel-buttons export-route="admin.guru.export" import-route="admin.guru.import" template-route="admin.guru.template" />
                </div>
            </div>

            <div class="tabs">
                <a href="{{ route('admin.siswa') }}" class="tab-item">Siswa</a>
                <a href="{{ route('admin.guru') }}" class="tab-item active">Guru</a>
                <a href="{{ route('admin.mapel') }}" class="tab-item">Mapel</a>
                <a href="{{ route('admin.kelas') }}" class="tab-item">Kelas</a>
            </div>

            <div class="filter-bar">
                <form method="GET" action="{{ route('admin.guru') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari nama atau nomor induk..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="btn btn-search">🔍 Cari</button>
                    @if(request('search'))
                    <a href="{{ route('admin.guru') }}" class="btn btn-reset">Reset</a>
                    @endif
                </form>
                @if(request('search'))
                <p class="filter-info">Menampilkan {{ $guru->total() }} hasil</p>
                @endif
            </div>

            <div class="excel-grid-wrapper">
                <div class="excel-hint">💡 Klik sel untuk mengedit langsung. Tekan Enter atau klik di luar untuk menyimpan.</div>
                <table class="excel-grid" id="guruGrid">
                    <thead>
                        <tr>
                            <th class="row-num">No</th>
                            <th>Nomor Induk</th>
                            <th>Nama Guru</th>
                            <th>Jenis Kelamin</th>
                            <th>Tipe</th>
                            <th>Thn. Masuk Kerja</th>
                            <th>Status</th>
                            <th class="col-action">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guru as $index => $g)
                        <tr data-id="{{ $g->id_user }}" data-url="{{ route('admin.guru.update', $g->id_user) }}">
                            <td class="row-num">{{ $guru->firstItem() + $index }}</td>
                            <td class="editable" data-field="nomor_induk">{{ $g->nomor_induk }}</td>
                            <td class="editable" data-field="name">{{ $g->user->name ?? '' }}</td>
                            <td class="editable" data-field="gender" data-type="select" data-options="M:Laki-laki,F:Perempuan">{{ $g->user->gender === 'M' ? 'Laki-laki' : ($g->user->gender === 'F' ? 'Perempuan' : '') }}</td>
                            <td class="editable" data-field="type" data-type="select" data-options="pns:PNS,honorer:Honorer">{{ ucfirst($g->type ?? '') }}</td>
                            <td class="editable" data-field="date_of_employment">{{ $g->date_of_employment ?? '' }}</td>
                            <td class="editable" data-field="teacher_status" data-type="select" data-options="employed:Aktif,resigned:Keluar">{{ $g->teacher_status === 'employed' ? 'Aktif' : ($g->teacher_status === 'resigned' ? 'Keluar' : '') }}</td>
                            <td class="col-action">
                                <button class="btn-delete-row" data-url="{{ route('admin.guru.delete', $g->id_user) }}" title="Hapus">🗑</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="empty-row">Tidak ada data guru</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($guru->hasPages())
            <div class="pagination-container">{{ $guru->links() }}</div>
            @endif
        </div>
    </main>
</div>
@include('admin._excel-grid-script')
</body>
</html>
