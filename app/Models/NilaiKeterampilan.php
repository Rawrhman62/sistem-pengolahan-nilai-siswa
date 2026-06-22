<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKeterampilan extends Model
{
    use HasFactory;

    protected $table = 'tb_nilai_keterampilan';
    protected $primaryKey = 'id_nilai_keterampilan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_nilai_keterampilan',
        'nilai',
    ];
}
