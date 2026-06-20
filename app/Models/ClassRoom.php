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

    public function homeroom()
    {
        return $this->hasOne(Homeroom::class, 'id_class', 'id_class');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_class', 'id_class');
    }
}
