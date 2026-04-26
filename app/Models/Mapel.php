<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'kode',
        'nama',
        'kelompok',
    ];

    /**
     * The columns that can be sorted.
     *
     * @var array<string>
     */
    protected $sortableColumns = [
        'kode',
        'nama',
        'kelompok',
    ];

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
