<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Kelas - E-RAPOR</title>
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
            --purple: #8B5CF6;
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
            background: linear-gradient(135deg, var(--purple), #A78BFA);
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

        .stat-card.purple {
            border-left-color: var(--purple);
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--purple);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr:hover {
            background-color: var(--bg-light);
        }

        .rank-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: var(--purple);
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: 700;
        }

        .rank-badge.gold {
            background: #F59E0B;
        }

        .rank-badge.silver {
            background: #94A3B8;
        }

        .rank-badge.bronze {
            background: #CD7F32;
        }

        .grade-display {
            font-weight: 700;
            font-size: 18px;
            color: var(--primary-blue);
        }

        .btn-detail {
            background: var(--primary-blue);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .btn-detail:hover {
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

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--green);
            transition: width 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Kembali ke Dashboard</a>
        
        <div class="header">
            <div>
                <h1>Wali Kelas {{ $kelasWali }}</h1>
                <p>Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card purple">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Rata-rata Kelas</div>
                <div class="stat-value">{{ number_format($rataRataKelas, 1) }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Nilai Lengkap</div>
                <div class="stat-value">{{ $siswaLengkap }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Belum Lengkap</div>
                <div class="stat-value">{{ $siswaBelumLengkap }}</div>
            </div>
        </div>

        <!-- Student Rankings -->
        <div class="card">
            <h2>Peringkat Siswa Kelas {{ $kelasWali }}</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Jumlah Mapel</th>
                        <th>Rata-rata Nilai</th>
                        <th>Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaWithGrades as $index => $data)
                    <tr>
                        <td>
                            <span class="rank-badge {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : '')) }}">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td>{{ $data['siswa']->user_id }}</td>
                        <td><strong>{{ $data['siswa']->name }}</strong></td>
                        <td>{{ $data['jumlah_mapel'] }} / 8</td>
                        <td>
                            <span class="grade-display">{{ $data['rata_rata'] }}</span>
                        </td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($data['jumlah_mapel'] / 8) * 100 }}%"></div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('wali_kelas.detail', $data['siswa']->user_id) }}" class="btn-detail">Lihat Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
