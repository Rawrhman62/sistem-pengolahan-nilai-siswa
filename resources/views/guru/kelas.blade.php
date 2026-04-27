<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - Kelas {{ $kelasNama }} - E-RAPOR</title>
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
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--primary-blue);
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

        .input-nilai {
            width: 80px;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
        }

        .input-nilai:focus {
            outline: none;
            border-color: var(--primary-blue);
        }

        .btn-save {
            background: var(--green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-save:hover {
            background: #059669;
        }

        .nilai-display {
            font-weight: 600;
            color: var(--primary-blue);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('guru.index') }}" class="back-link">← Kembali ke Dashboard</a>
        
        <div class="header">
            <h1>Input Nilai - Kelas {{ $kelasNama }}</h1>
            <p>Mata Pelajaran: Matematika | Semester: Ganjil (1) | Tahun Ajaran: 2025/2026</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Nilai Pengetahuan</th>
                        <th>Nilai Keterampilan</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $index => $siswa)
                    @php
                        $nilai = $nilaiList->get($siswa->user_id);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $siswa->user_id }}</td>
                        <td>{{ $siswa->name }}</td>
                        <td>
                            @if($nilai)
                                <span class="nilai-display">{{ $nilai->nilai_pengetahuan }}</span>
                            @else
                                <form method="POST" action="{{ route('guru.simpanNilai') }}" style="display: inline;" id="form-{{ $siswa->user_id }}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $siswa->user_id }}">
                                    <input type="hidden" name="mapel" value="Matematika">
                                    <input type="hidden" name="semester" value="Ganjil (1)">
                                    <input type="number" name="nilai_pengetahuan" class="input-nilai" min="0" max="100" placeholder="0-100">
                            @endif
                        </td>
                        <td>
                            @if($nilai)
                                <span class="nilai-display">{{ $nilai->nilai_keterampilan }}</span>
                            @else
                                    <input type="number" name="nilai_keterampilan" class="input-nilai" min="0" max="100" placeholder="0-100">
                            @endif
                        </td>
                        <td>
                            @if($nilai)
                                <span class="nilai-display">{{ $nilai->nilai_akhir }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($nilai)
                                <strong>{{ $nilai->predikat }}</strong>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($nilai)
                                <span style="color: var(--green); font-weight: 600;">✓ Tersimpan</span>
                            @else
                                    <button type="submit" class="btn-save">Simpan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <!-- Dummy Data 1: Belum dinilai -->
                    <tr>
                        <td>1</td>
                        <td>25261001</td>
                        <td>Ahmad Budi Santoso</td>
                        <td>
                            <form method="POST" action="#" style="display: inline;" onsubmit="event.preventDefault(); alert('Ini hanya data dummy!');">
                                @csrf
                                <input type="number" name="nilai_pengetahuan" class="input-nilai" min="0" max="100" placeholder="0-100">
                        </td>
                        <td>
                                <input type="number" name="nilai_keterampilan" class="input-nilai" min="0" max="100" placeholder="0-100">
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                                <button type="submit" class="btn-save">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Dummy Data 2: Sudah dinilai -->
                    <tr>
                        <td>2</td>
                        <td>25261002</td>
                        <td>Bunga Citra Lestari</td>
                        <td>
                            <span class="nilai-display">85</span>
                        </td>
                        <td>
                            <span class="nilai-display">88</span>
                        </td>
                        <td>
                            <span class="nilai-display">86.5</span>
                        </td>
                        <td>
                            <strong>A-</strong>
                        </td>
                        <td>
                            <span style="color: var(--green); font-weight: 600;">✓ Tersimpan</span>
                        </td>
                    </tr>
                    <!-- Dummy Data 3: Belum dinilai -->
                    <tr>
                        <td>3</td>
                        <td>25261003</td>
                        <td>Cahyo Dimas Pratama</td>
                        <td>
                            <form method="POST" action="#" style="display: inline;" onsubmit="event.preventDefault(); alert('Ini hanya data dummy!');">
                                @csrf
                                <input type="number" name="nilai_pengetahuan" class="input-nilai" min="0" max="100" placeholder="0-100">
                        </td>
                        <td>
                                <input type="number" name="nilai_keterampilan" class="input-nilai" min="0" max="100" placeholder="0-100">
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                                <button type="submit" class="btn-save">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
