<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nilai - {{ $siswa->name }} - E-RAPOR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0A2E5C;
            --bg-light: #F4F7F6;
            --text-dark: #333333;
            --text-gray: #666666;
            --white: #FFFFFF;
            --border-color: #E2E8F0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            background: linear-gradient(135deg, var(--purple), #A78BFA);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .student-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            opacity: 0.95;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
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

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
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

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .summary-card {
            background: var(--bg-light);
            padding: 25px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-label {
            font-size: 13px;
            color: var(--text-gray);
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 48px;
            font-weight: 700;
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('wali_kelas.index') }}" class="back-link">← Kembali ke Dashboard</a>
        
        <div class="header">
            <h1>Detail Nilai Siswa</h1>
            <div class="student-info">
                <div class="info-item">
                    <span class="info-label">Nama Siswa</span>
                    <span class="info-value">{{ $siswa->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIS</span>
                    <span class="info-value">{{ $siswa->user_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rata-rata</span>
                    <span class="info-value">{{ number_format($rataRata, 1) }}</span>
                </div>
            </div>
        </div>

        <!-- Grades Table -->
        <div class="card">
            <h2>Daftar Nilai</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Nilai Pengetahuan</th>
                        <th>Nilai Keterampilan</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $n)
                    <tr>
                        <td><strong>{{ $n->mapel }}</strong></td>
                        <td>{{ $n->nilai_pengetahuan }}</td>
                        <td>{{ $n->nilai_keterampilan }}</td>
                        <td><strong>{{ $n->nilai_akhir }}</strong></td>
                        <td><strong style="font-size: 18px; color: var(--purple);">{{ $n->predikat }}</strong></td>
                    </tr>
                    @empty
                    <!-- Dummy Data 1 -->
                    <tr>
                        <td><strong>Matematika</strong></td>
                        <td>85</td>
                        <td>88</td>
                        <td><strong>86.5</strong></td>
                        <td><strong style="font-size: 18px; color: var(--purple);">A</strong></td>
                    </tr>
                    <!-- Dummy Data 2 -->
                    <tr>
                        <td><strong>Bahasa Indonesia</strong></td>
                        <td>90</td>
                        <td>85</td>
                        <td><strong>87.5</strong></td>
                        <td><strong style="font-size: 18px; color: var(--purple);">A</strong></td>
                    </tr>
                    <!-- Dummy Data 3 -->
                    <tr>
                        <td><strong>IPA</strong></td>
                        <td>82</td>
                        <td>80</td>
                        <td><strong>81.0</strong></td>
                        <td><strong style="font-size: 18px; color: var(--purple);">B</strong></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Rata-rata Nilai</div>
                <div class="summary-value">{{ $nilai->count() > 0 ? number_format($rataRata, 1) : '85.0' }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Jumlah Mata Pelajaran</div>
                <div class="summary-value">{{ $nilai->count() > 0 ? $nilai->count() : '3' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
