<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'tb_subjects';

    protected $fillable = [
        'id_subjects',
        'code',
        'name',
        'grade',
        'curriculum',
        'group',
    ];
}
