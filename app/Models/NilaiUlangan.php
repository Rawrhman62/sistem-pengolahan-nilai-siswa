<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiUlangan extends Model
{
    use HasFactory;

    protected $table = 'tb_nilai_ulangan';
    protected $primaryKey = 'id_nilai_ulangan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_nilai_ulangan',
        'ulangan_1',
        'ulangan_2',
        'ulangan_3',
        'ulangan_4',
        'ulangan_5',
        'ulangan_6',
        'ulangan_7',
        'ulangan_8',
    ];
}
