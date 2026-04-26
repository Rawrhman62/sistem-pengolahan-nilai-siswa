<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'nip',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'alamat',
        'pendidikan_terakhir',
        'gelar',
        'tanggal_bergabung',
    ];

    /**
     * The columns that can be sorted.
     *
     * @var array<string>
     */
    protected $sortableColumns = [
        'nip',
        'tanggal_lahir',
        'tanggal_bergabung',
    ];

    /**
     * Get the user that owns the guru record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the teacher's name from the related user.
     */
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
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
