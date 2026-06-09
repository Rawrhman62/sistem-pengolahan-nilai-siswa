<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectCode extends Model
{
    use HasFactory;

    protected $table = 'tb_subjects_code';

    protected $fillable = [
        'code',
        'name',
    ];
}
