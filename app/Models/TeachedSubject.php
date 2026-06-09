<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachedSubject extends Model
{
    use HasFactory;

    protected $table = 'tb_teached_subjects';

    protected $fillable = [
        'id_user',
        'id_subjects',
        'teached_class',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subjects', 'id_subjects');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'teached_class', 'id_class');
    }
}
