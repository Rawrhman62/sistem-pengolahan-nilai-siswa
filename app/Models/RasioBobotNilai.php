<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RasioBobotNilai extends Model
{
    use HasFactory;

    protected $table = 'tb_rasio_bobot_nilai';

    protected $fillable = [
        'nilai_harian',
        'nilai_keterampilan',
        'nilai_ulangan',
        'nilai_ujian',
        'void',
    ];
}
