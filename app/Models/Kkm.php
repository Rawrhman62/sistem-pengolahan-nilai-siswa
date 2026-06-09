<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kkm extends Model
{
    use HasFactory;

    protected $table = 'tb_kkm';

    protected $fillable = [
        'grade',
        'kkm',
    ];
}
