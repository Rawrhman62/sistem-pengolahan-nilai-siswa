<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - E-RAPOR</title>
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
            --orange: #F59E0B;
            --blue: #3B82F6;
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

        .header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-hover));
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border-left: 4px solid var(--blue);
        }

        .stat-card.green {
            border-left-color: var(--green);
        }

        .stat-card.orange {
            border-left-color: var(--orange);
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-gray);
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--card-shadow);
        }

        .card h2 {
            font-size: 20px;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        .class-list {
            display: grid;
            gap: 15px;
        }

        .class-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .class-item:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 2px 8px rgba(10, 46, 92, 0.1);
        }

        .class-info h3 {
            font-size: 18px;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .class-info p {
            font-size: 14px;
            color: var(--text-gray);
        }

        .class-meta {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .meta-item {
            text-align: center;
        }

        .meta-label {
            font-size: 11px;
            color: var(--text-gray);
            text-transform: uppercase;
        }

        .meta-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .btn-input {
            background: var(--primary-blue);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-input:hover {
            background: var(--primary-blue-hover);
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
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Kembali ke Dashboard</a>
        
        <div class="header">
            <div>
                <h1>Guru Mapel</h1>
                <p>Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        @if(session('success'))
            <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Kelas</div>
                <div class="stat-value">{{ $totalKelas }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Nilai Diinput</div>
                <div class="stat-value">{{ $nilaiDiinput }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Belum Diinput</div>
                <div class="stat-value">{{ $nilaiBelumDiinput }}</div>
            </div>
        </div>

        <!-- Class List -->
        <div class="card">
            <h2>Daftar Kelas yang Diampu</h2>
            
            <div class="class-list">
                @foreach($kelasList as $kelas)
                <div class="class-item">
                    <div class="class-info">
                        <h3>Kelas {{ $kelas['nama'] }}</h3>
                        <p>Mata Pelajaran: {{ $kelas['mapel'] }}</p>
                    </div>
                    
                    <div class="class-meta">
                        <div class="meta-item">
                            <div class="meta-label">Siswa</div>
                            <div class="meta-value">{{ $kelas['jumlah_siswa'] }}</div>
                        </div>
                        <a href="{{ route('guru.kelas', $kelas['nama']) }}" class="btn-input">Input Nilai</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
