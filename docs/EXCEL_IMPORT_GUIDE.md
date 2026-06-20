# Panduan Import Excel - E-Rapor

## Cara Menggunakan Fitur Import/Export Excel

### 1. Export Data (Download Data Saat Ini)

1. Buka halaman admin: **Siswa**, **Guru**, **Mapel**, atau **Kelas**
2. Klik tombol **"📊 Export Excel"**
3. File Excel akan terdownload dengan semua data yang ada

### 2. Download Template (Format Kosong)

1. Klik tombol **"📄 Download Template"**
2. Anda akan mendapat file Excel kosong dengan header yang benar
3. Gunakan template ini sebagai panduan untuk format yang benar

### 3. Import Data (Upload Data Baru)

1. Siapkan file Excel Anda (bisa dari template atau hasil export)
2. Klik tombol **"📥 Import Excel"**
3. Pilih file Excel Anda
4. Sistem akan:
   - Validasi semua data
   - Tampilkan baris mana yang berhasil/gagal
   - Buat akun User otomatis jika diperlukan

## Format Template untuk Setiap Jenis Data

### 📚 Template Siswa

| NIS | Nama Lengkap | NISN | Tahun Masuk | Nama Kelas | Jenis Kelamin (M/F) |
|-----|--------------|------|-------------|------------|---------------------|
| 240001 | Ahmad Rizki | 1234567890 | 2024 | 7A | M |
| 240002 | Siti Nurhaliza | 9876543210 | 2024 | 7B | F |

**Penjelasan Kolom:**
- **NIS**: Nomor Induk Siswa (wajib, unik)
- **Nama Lengkap**: Nama lengkap siswa (wajib)
- **NISN**: Nomor Induk Siswa Nasional (opsional)
- **Tahun Masuk**: Tahun masuk sekolah, contoh: 2024 (opsional)
- **Nama Kelas**: Nama kelas, contoh: "7A", "8B", "9C" (opsional)
- **Jenis Kelamin**: Isi dengan **M** untuk Laki-laki atau **F** untuk Perempuan (opsional)

**Tips:**
- NIS harus unik, tidak boleh sama
- Jika Nama Kelas tidak ada di database, siswa akan dibuat tanpa kelas (bisa diatur nanti)
- Sistem akan otomatis membuat akun User untuk setiap siswa

---

### 👨‍🏫 Template Guru

| Nomor Induk | Nama Lengkap | Tipe (pns/honorer) | Tahun Masuk Kerja | Status (employed/resigned) | Jenis Kelamin (M/F) |
|-------------|--------------|-------------------|-------------------|---------------------------|---------------------|
| 196501011990 | Budi Santoso | pns | 1990 | employed | M |
| 198803152010 | Ani Wijaya | honorer | 2010 | employed | F |

**Penjelasan Kolom:**
- **Nomor Induk**: NIP atau nomor identitas guru (wajib, unik)
- **Nama Lengkap**: Nama lengkap guru (wajib)
- **Tipe**: Isi dengan **pns** atau **honorer** (opsional)
- **Tahun Masuk Kerja**: Tahun mulai bekerja, contoh: 1990 (opsional)
- **Status**: Isi dengan **employed** (aktif) atau **resigned** (keluar) (opsional)
- **Jenis Kelamin**: Isi dengan **M** atau **F** (opsional)

**Tips:**
- Nomor Induk harus unik
- Sistem otomatis membuat akun User dan role Lecturer untuk setiap guru
- Default status adalah "employed" jika tidak diisi

---

### 📖 Template Mata Pelajaran

| Kode Mapel | Nama Mata Pelajaran | Tingkat Kelas | Kurikulum | Kelompok |
|------------|---------------------|---------------|-----------|----------|
| MTK | Matematika | 7 | Merdeka | IPTEK |
| IPA | Ilmu Pengetahuan Alam | 8 | Merdeka | IPTEK |
| BIN | Bahasa Indonesia | 7 | Merdeka | Bahasa |

**Penjelasan Kolom:**
- **Kode Mapel**: Kode singkat mata pelajaran (opsional)
- **Nama Mata Pelajaran**: Nama lengkap mata pelajaran (wajib)
- **Tingkat Kelas**: Tingkat kelas 1-12 (opsional)
- **Kurikulum**: Nama kurikulum, contoh: "Merdeka", "K13" (opsional)
- **Kelompok**: Kelompok mata pelajaran, contoh: "IPTEK", "Bahasa", "Agama" (opsional)

**Tips:**
- Nama Mata Pelajaran harus unik
- Kode Mapel bisa dikosongkan jika tidak ada

---

### 🏫 Template Kelas

| Nama Kelas | Tingkat |
|------------|---------|
| 7A | 7 |
| 7B | 7 |
| 8A | 8 |
| 8B | 8 |

**Penjelasan Kolom:**
- **Nama Kelas**: Nama kelas (wajib, unik)
- **Tingkat**: Tingkat kelas 1-12 (opsional)

**Tips:**
- Nama Kelas harus unik
- Format umum: [Tingkat][Kelas], contoh: "7A", "10 IPA 1"

## Pesan Error Umum dan Solusinya

### ❌ "User with NIS/NIP xxx already exists"
**Penyebab**: Data dengan NIS/NIP tersebut sudah ada di database  
**Solusi**: 
- Hapus baris tersebut dari Excel jika duplikat
- Atau gunakan NIS/NIP yang berbeda
- Atau update data yang sudah ada melalui halaman edit

### ❌ "The name field is required"
**Penyebab**: Kolom Nama Lengkap kosong  
**Solusi**: Isi kolom Nama Lengkap (wajib diisi)

### ❌ "The nis field is required"
**Penyebab**: Kolom NIS kosong untuk siswa  
**Solusi**: Isi kolom NIS dengan nomor unik

### ❌ "Missing required columns: xxx"
**Penyebab**: Header Excel tidak sesuai template  
**Solusi**: Download template terbaru dan pastikan nama kolom sama persis

## Tips Penggunaan Excel

### ✅ Best Practices

1. **Selalu gunakan template terbaru** - Download template sebelum import
2. **Jangan ubah nama kolom** - Nama header harus sama persis dengan template
3. **Backup data** - Export data sebelum melakukan import besar
4. **Test dengan data kecil** - Coba import 2-3 baris dulu untuk memastikan format benar
5. **Periksa error message** - Baca pesan error dengan teliti untuk memperbaiki data

### ✅ Workflow yang Disarankan

**Untuk Update Data:**
```
1. Export data saat ini → Download file Excel
2. Edit file Excel (tambah/ubah/hapus baris)
3. Import kembali → Upload file yang sudah diedit
```

**Untuk Data Baru:**
```
1. Download Template → File Excel kosong dengan header
2. Isi data baru
3. Import → Upload file
```

## Fitur Canggih

### ✨ Partial Import (Import Sebagian)

Jika beberapa baris ada yang error, sistem tetap akan mengimport baris yang benar.

**Contoh:**
- Row 1: ✓ Berhasil
- Row 2: ✗ Error (NIS duplikat)
- Row 3: ✓ Berhasil

**Result**: 2 data berhasil diimport, 1 data gagal dengan penjelasan error

### ✨ Auto-Create User Accounts

Untuk siswa dan guru, sistem otomatis membuat:
- User account dengan username = NIS/NIP
- Password default (NULL) - harus diganti di halaman settings
- Role yang sesuai (Student untuk siswa, Lecturer untuk guru)

### ✨ Class Lookup by Name

Saat import siswa, cukup tulis nama kelas (contoh: "7A")
- Sistem akan mencari kelas dengan nama tersebut
- Jika tidak ada, siswa dibuat tanpa kelas
- Bisa diassign kelas nanti melalui halaman edit

## Contoh Skenario Penggunaan

### Skenario 1: Import 100 Siswa Baru

1. Download template siswa
2. Isi 100 baris dengan data siswa
3. Pastikan NIS unik untuk setiap siswa
4. Isi Nama Lengkap (wajib)
5. Isi kolom lain sesuai kebutuhan
6. Import file
7. Periksa hasil: "Successfully imported 100 siswa record(s)."

### Skenario 2: Update Kelas Siswa

1. Export data siswa saat ini
2. Buka file Excel
3. Edit kolom "Nama Kelas" sesuai kebutuhan
4. Hapus semua data siswa lama dari database (atau gunakan fitur bulk update)
5. Import file yang sudah diedit
6. Siswa akan diupdate dengan kelas baru

### Skenario 3: Tambah Mata Pelajaran Kurikulum Baru

1. Download template mapel
2. Isi data mata pelajaran baru:
   - Nama Mata Pelajaran
   - Kurikulum: "Merdeka"
   - Kelompok sesuai kategori
3. Import file
4. Mata pelajaran baru siap digunakan

## Troubleshooting

### File tidak bisa diupload?

**Cek:**
- Format file: Harus .xlsx atau .xls
- Ukuran file: Maksimal 5MB
- Browser: Coba refresh halaman

### Import gagal semua?

**Cek:**
- Header kolom: Harus sama persis dengan template
- Data required: Pastikan kolom wajib diisi
- Format data: Tahun harus angka, jenis kelamin harus M/F

### Sebagian data tidak masuk?

**Cek pesan error:**
- Sistem akan memberitahu baris mana yang gagal
- Perbaiki baris yang error
- Import ulang file yang sudah diperbaiki

## Kontak Support

Jika mengalami masalah:
1. Baca error message dengan teliti
2. Cek format data sesuai panduan ini
3. Download template terbaru
4. Hubungi administrator sistem jika masalah berlanjut

---

**Last Updated**: June 2026  
**System Version**: E-Rapor v2.0
