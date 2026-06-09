<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homeroom extends Model
{
    use HasFactory;

    protected $table = 'tb_homerooms';

    protected $fillable = [
        'id_user',
        'id_class',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'id_class', 'id_class');
    }
}
