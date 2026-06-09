<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predikat extends Model
{
    use HasFactory;

    protected $table = 'tb_predikat';

    protected $fillable = [
        'grade',
        'a_range',
        'b_range',
        'c_range',
        'd_range',
    ];
}
