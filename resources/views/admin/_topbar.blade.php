<header class="topbar">
    <div class="topbar-left">
        <h2>E-Rapor Digital</h2>
    </div>
    <div class="topbar-right">
        <div class="academic-year">
            <span>Tahun Pelajaran</span>
            <strong>
                @php
                    $ta = \App\Models\TahunAjaranDate::getCurrentTahunAjaran();
                    $sem = \App\Models\TahunAjaranDate::getCurrentSemester();
                @endphp
                {{ $ta ? $ta->tahun_ajaran : '2025/2026' }}
                ({{ $sem == 1 ? 'Ganjil' : 'Genap' }})
            </strong>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <strong>{{ auth()->user()->name ?? 'Admin Sekolah' }}</strong>
                <span>ADMINISTRATOR</span>
            </div>
            <div class="avatar">{{ substr(auth()->user()->name ?? 'AS', 0, 2) }}</div>
        </div>
    </div>
</header>
