<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'tb_teachers';

    protected $fillable = [
        'id_user',
        'nomor_induk',
        'date_of_employment',
        'teacher_status',
        'type',
    ];

    /**
     * Validation rules for the model
     *
     * @var array
     */
    public static function rules()
    {
        return [
            'nomor_induk' => 'required|string|unique:tb_teachers,nomor_induk',
            'id_user' => 'required|exists:tb_users,id_user',
            'date_of_employment' => 'nullable|integer',
            'teacher_status' => 'nullable|string',
            'type' => 'nullable|string',
        ];
    }

    /**
     * Find a teacher by their nomor_induk
     *
     * @param string $nomorInduk
     * @return Teacher|null
     */
    public static function findByNomorInduk(string $nomorInduk)
    {
        return static::where('nomor_induk', $nomorInduk)->first();
    }

    /**
     * Define relationship to User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
