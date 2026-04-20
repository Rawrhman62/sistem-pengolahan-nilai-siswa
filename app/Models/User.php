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
}
