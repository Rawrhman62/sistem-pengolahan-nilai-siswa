<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'tb_nilai';

    protected $fillable = [
        'id_user',
        'id_nilai',
        'id_nilai_harian',
        'id_nilai_keterampilan',
        'id_nilai_ulangan',
        'id_nilai_ujian',
        'id_subjects',
        'semester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subjects', 'id_subjects');
    }

    public function nilaiHarian()
    {
        return $this->belongsTo(NilaiHarian::class, 'id_nilai_harian', 'id_nilai_harian');
    }

    public function nilaiKeterampilan()
    {
        return $this->belongsTo(NilaiKeterampilan::class, 'id_nilai_keterampilan', 'id_nilai_keterampilan');
    }

    public function nilaiUlangan()
    {
        return $this->belongsTo(NilaiUlangan::class, 'id_nilai_ulangan', 'id_nilai_ulangan');
    }

    public function nilaiUjian()
    {
        return $this->belongsTo(NilaiUjian::class, 'id_nilai_ujian', 'id_nilai_ujian');
    }
}
