<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama',
        'tingkat',
        'wali_kelas',
        'wali_kelas_id',
        'kapasitas',
        'jumlah_siswa',
        'ruangan',
        'tahun_ajaran',
    ];

    /**
     * The columns that can be sorted.
     *
     * @var array<string>
     */
    protected $sortableColumns = [
        'nama',
        'tingkat',
        'kapasitas',
        'jumlah_siswa',
    ];

    /**
     * Get the homeroom teacher (wali kelas) for this class.
     */
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * Get the homeroom teacher's name.
     */
    public function getWaliKelasNamaAttribute()
    {
        return $this->waliKelas ? $this->waliKelas->name : $this->wali_kelas;
    }

    /**
     * Scope a query to sort by a given column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $column
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortBy($query, ?string $column, string $direction = 'asc')
    {
        if (!$column) {
            return $query;
        }

        if (in_array($column, $this->sortableColumns)) {
            return $query->orderBy($column, $direction);
        }

        return $query;
    }
}
