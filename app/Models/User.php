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

    protected $table = 'tb_users';
    protected $primaryKey = 'id_user';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'id_user',
        'gender',
        'email',
        'phone_number',
        'password',
        'profile_picture',
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
        'id_user',
        'email',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id_user;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Check if user has set a password.
     * This checks the raw database value to determine if password exists.
     *
     * @return bool
     */
    public function getPasswordSetAttribute()
    {
        // Check if password exists in database (not NULL)
        $password = \DB::table('tb_users')->where('id_user', $this->id_user)->value('password');
        return !is_null($password);
    }

    // Role relationships
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user', 'id_user');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'id_user', 'id_user');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'id_user', 'id_user');
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class, 'id_user', 'id_user');
    }

    public function homerooms()
    {
        return $this->hasMany(Homeroom::class, 'id_user', 'id_user');
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->student()->exists();
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->teacher()->exists();
    }

    /**
     * Check if user is a lecturer.
     */
    public function isLecturer(): bool
    {
        return $this->lecturer()->exists();
    }

    /**
     * Check if user is a homeroom teacher.
     */
    public function isHomeroomTeacher(): bool
    {
        return $this->homerooms()->exists();
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->admin()->exists();
    }

    /**
     * Get all roles as an array.
     */
    public function getRoles(): array
    {
        $roles = [];
        if ($this->isAdmin()) {
            $roles[] = 'administrator';
        }
        if ($this->isLecturer()) {
            $roles[] = 'lectureTeacher';
        }
        if ($this->isHomeroomTeacher()) {
            $roles[] = 'homeroomTeacher';
        }
        if ($this->isStudent()) {
            $roles[] = 'student';
        }
        
        return $roles;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Get the currently selected role from session.
     */
    public function getCurrentRole(): string
    {
        $selectedRole = session('selected_role');
        
        // If no role in session or invalid role, return first role
        if (!$selectedRole || !$this->hasRole($selectedRole)) {
            $roles = $this->getRoles();
            return !empty($roles) ? $roles[0] : '';
        }
        
        return $selectedRole;
    }

    /**
     * Check if user is currently acting as admin.
     */
    public function isCurrentlyAdmin(): bool
    {
        return $this->getCurrentRole() === 'administrator';
    }

    /**
     * Check if user is currently acting as a teacher.
     */
    public function isCurrentlyTeacher(): bool
    {
        $currentRole = $this->getCurrentRole();
        return $currentRole === 'lectureTeacher' || $currentRole === 'homeroomTeacher';
    }

    /**
     * Scope a query to sort by a given column.
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
