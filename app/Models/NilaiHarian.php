<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiHarian extends Model
{
    use HasFactory;

    protected $table = 'tb_nilai_harian';
    protected $primaryKey = 'id_nilai_harian';
    public $incrementing = true;

    protected $fillable = [
        'id_nilai_harian',
        'minggu_1',
        'minggu_2',
        'minggu_3',
        'minggu_4',
        'minggu_5',
        'minggu_6',
        'minggu_7',
        'minggu_8',
        'minggu_9',
        'minggu_10',
        'minggu_11',
        'minggu_12',
    ];
}
