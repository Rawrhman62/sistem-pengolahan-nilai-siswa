<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'user_id',
        'mapel',
        'nilai_pengetahuan',
        'nilai_keterampilan',
        'nilai_akhir',
        'predikat',
        'semester',
    ];

    /**
     * Get the user that owns the nilai.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
