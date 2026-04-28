<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-RAPOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --secondary: #10B981;
            --bg-color: #F3F4F6;
            --card-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border: #E5E7EB;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: var(--card-bg);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
        }

        .navbar-brand .logo {
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            color: white;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 1.2rem;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .navbar-menu a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            transition: color 0.2s;
        }

        .navbar-menu a:hover {
            color: var(--primary);
        }

        .btn-logout {
            background-color: #EF4444;
            color: white;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            font-size: 0.9rem;
        }

        .btn-logout:hover {
            background-color: #DC2626;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            border-radius: 16px;
            padding: 3rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;utf8,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.1)" stroke-width="2" fill="none"/></svg>') repeat;
            opacity: 0.5;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .hero p {
            font-size: 1.05rem;
            opacity: 0.9;
        }
        
        .role-badge {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            padding: 0 1.25rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            border: 1px solid rgba(255,255,255,0.2);
            height: 46px;
            color: white;
        }

        /* Dashboard Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-body p {
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        /* Form elements */
        .switch-role-container {
            background: rgba(255,255,255,0.1);
            padding: 0 1.25rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
            height: 46px;
        }

        select.form-select {
            padding: 0.5rem 2rem 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.9);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: var(--text-main);
            cursor: pointer;
            outline: none;
            margin-left: 0.75rem;
            font-weight: 500;
        }

        /* Action Links */
        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background: rgba(79, 70, 229, 0.05);
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .action-link:hover {
            background: rgba(79, 70, 229, 0.1);
            transform: translateX(5px);
        }

        .action-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            .hero {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="logo">E</div>
            E-RAPOR
        </div>
        <div class="navbar-menu">
            <form method="POST" action="/logout" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <div class="hero-content">
                <h1>Selamat Datang Kembali, {{ $user->user_name }}!</h1>
                <p>Tetap Semangat Mencerdaskan Masa Depan Bangsa!</p>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
                    <div class="role-badge">
                        Anda adalah {{ $role === 'lectureTeacher' ? 'Guru Mapel' : ($role === 'homeroomTeacher' ? 'Wali Kelas' : ($role === 'administrator' ? 'Administrator' : ($role === 'student' ? 'Siswa' : ucfirst($role)))) }}
                    </div>

                    @if(count($allRoles) > 1)
                    <div class="switch-role-container">
                        <form method="POST" action="/switch-role" style="display: flex; align-items: center;">
                            @csrf
                            <span style="font-size: 0.95rem; font-weight: 500; color: white;">Masuk Sebagai:</span>
                            <select name="role" class="form-select" onchange="this.form.submit()">
                                @foreach($allRoles as $availableRole)
                                    <option value="{{ $availableRole }}" {{ $role === $availableRole ? 'selected' : '' }}>
                                        {{ $availableRole === 'lectureTeacher' ? 'Guru Mapel' : 
                                           ($availableRole === 'homeroomTeacher' ? 'Wali Kelas' : 
                                           ucfirst($availableRole)) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Role Specific Content -->
        <div class="grid">
            
            @if($isAdmin)
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🥀</div>
                    <div class="card-title">Admin telah tiba!</div>
                </div>
                <div class="card-body">
                    <p></p>
                    <div class="action-list">
                        <a href="/admin" class="action-link"><span>📊</span> Pergi ke Dashboard Admin</a>
                        <a href="/admin/register" class="action-link"><span>+</span> Tambah Pengguna Baru</a>
                        <a href="/admin/manage" class="action-link"><span>👥</span> Kelola Pengguna</a>
                    </div>
                </div>
            </div>
            @endif

            @if($isTeacher)
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">👨‍🏫</div>
                    <div class="card-title">Dashboard</div>
                </div>
                <div class="card-body">
                    @if($role === 'lectureTeacher')
                        <p>Anda sedang masuk sebagai <strong>Guru Mapel</strong>. </p>
                        <div class="action-list">                            
                            <a href="{{ route('guru.index') }}" class="action-link"><span>📚</span> Input Nilai Siswa</a>
                        </div>
                    @elseif($role === 'homeroomTeacher')
                        <p>Anda sedang masuk sebagai <strong>Wali Kelas</strong>. </p>
                        <div class="action-list">                            
                            <a href="{{ route('wali_kelas.index') }}" class="action-link"><span>📋</span> Lihat Leger Nilai</a>                                                        
                            <a href="#" class="action-link"><span>📑</span> Cetak Raport</a>
                        </div>
                    @else
                        <p>You have teacher privileges. Select a specific teacher role to view options.</p>
                    @endif
                </div>
            </div>
            @endif

            @if(!$isAdmin && !$isTeacher)
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🎓</div>
                    <div class="card-title">Student Dashboard</div>
                </div>
                <div class="card-body">
                    <p>Lihat progress kemajuan kalian sobat!</p>
                    <div class="action-list">                       
                        <a href="{{ route('siswa.index') }}" class="action-link"><span>📅</span> Lihat Hasil Raport</a>
                        <a href="{{ route('siswa.profile') }}" class="action-link"><span>👤</span> Lihat Profil</a>                    
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="card-icon">⚙️</div>
                    <div class="card-title">Pengaturan Akun</div>
                </div>
                <div class="card-body">
                    <p> </p>
                    <div class="action-list">
                        <a href="/settings" class="action-link"><span>🔒</span> Ganti Password</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>