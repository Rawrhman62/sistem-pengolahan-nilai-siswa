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
        <li class="menu-item {{ ($active ?? '') === 'data' ? 'active' : '' }}">
            <a href="{{ route('admin.siswa') }}"><i>📁</i> Kelola Data</a>
        </li>
        <li class="menu-item {{ ($active ?? '') === 'manage' ? 'active' : '' }}">
            <a href="{{ route('admin.manage') }}"><i>👥</i> Manajemen User</a>
        </li>
        <li class="menu-item {{ ($active ?? '') === 'tahun' ? 'active' : '' }}">
            <a href="{{ route('admin.tahun-ajaran') }}"><i>📅</i> Kelola Tahun Ajaran</a>
        </li>
        <li class="menu-item {{ ($active ?? '') === 'excelconfig' ? 'active' : '' }}">
            <a href="{{ route('admin.excelconfig') }}"><i>📊</i> Konfigurasi Excel</a>
        </li>
    </ul>

    <div style="padding:20px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:100%;padding:12px;background:rgba(255,255,255,.1);color:#fff;border:none;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-size:14px;font-weight:500;">
                ↪ Keluar
            </button>
        </form>
    </div>
</aside>
