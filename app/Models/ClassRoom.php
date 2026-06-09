<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'tb_classes';

    protected $fillable = [
        'id_class',
        'grade',
        'name',
    ];
}
