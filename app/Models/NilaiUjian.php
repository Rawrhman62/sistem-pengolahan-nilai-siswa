<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiUjian extends Model
{
    use HasFactory;

    protected $table = 'tb_nilai_ujian';
    protected $primaryKey = 'id_nilai_ujian';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_nilai_ujian',
        'awal_ganjil',
        'akhir_ganjil',
        'awal_genap',
        'akhir_genap',
        'year',
    ];
}
