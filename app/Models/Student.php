<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'tb_students';

    protected $fillable = [
        'id_user',
        'nis',
        'nisn',
        'entry_year',
        'id_class',
    ];

    /**
     * Validation rules for the model
     */
    public static function rules()
    {
        return [
            'nis' => 'required|string|unique:tb_students,nis',
            'id_user' => 'required|exists:tb_users,id_user',
            'nisn' => 'nullable|string',
            'entry_year' => 'nullable|integer',
            'id_class' => 'nullable|exists:tb_classes,id_class',
        ];
    }

    /**
     * Find a student by their NIS (Student Identification Number)
     * Used for authentication lookups
     * 
     * @param string $nis
     * @return Student|null
     */
    public static function findByNis(string $nis): ?Student
    {
        return static::where('nis', $nis)->first();
    }

    /**
     * Relationship to User model using id_user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'id_class', 'id_class');
    }
}
