<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'user_id',
        'email',
        'phone_number',
        'role',
        'password_set',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The columns that can be sorted.
     *
     * @var array<string>
     */
    protected $sortableColumns = [
        'name',
        'user_name',
        'email',
        'role',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_set' => 'boolean',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $roles = explode(',', $this->role);
        return in_array($role, array_map('trim', $roles));
    }

    /**
     * Check if user is a teacher (either lecture or homeroom).
     *
     * @return bool
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('lectureTeacher') || $this->hasRole('homeroomTeacher');
    }

    /**
     * Check if user is an administrator.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('administrator');
    }

    /**
     * Get all roles as an array.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return array_map('trim', explode(',', $this->role));
    }

    /**
     * Get the currently selected role from session.
     *
     * @return string
     */
    public function getCurrentRole(): string
    {
        $selectedRole = session('selected_role');
        
        // If no role in session or invalid role, return first role
        if (!$selectedRole || !$this->hasRole($selectedRole)) {
            return $this->getRoles()[0];
        }
        
        return $selectedRole;
    }

    /**
     * Check if user is currently acting as admin.
     *
     * @return bool
     */
    public function isCurrentlyAdmin(): bool
    {
        return $this->getCurrentRole() === 'administrator';
    }

    /**
     * Check if user is currently acting as a teacher.
     *
     * @return bool
     */
    public function isCurrentlyTeacher(): bool
    {
        $currentRole = $this->getCurrentRole();
        return $currentRole === 'lectureTeacher' || $currentRole === 'homeroomTeacher';
    }

    /**
     * Get the siswa record for this user (if they are a student).
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    /**
     * Get the guru record for this user (if they are a teacher).
     */
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Get the classes where this user is the homeroom teacher.
     */
    public function kelasAsWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    /**
     * Check if user is a student.
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
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
