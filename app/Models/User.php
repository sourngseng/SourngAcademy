<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Modules\Core\Models\Campus;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'name_kh',
        'gender',
        'dob',
        'phone',
        'address',
        'email',
        'password',
        'username',
        'profile_image',
        'role',
        'campus_id',
        'status',
        'telegram_chat_id',
        'google_id',
        'facebook_id',
        'telegram_id',
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
            'dob' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the campus that the user belongs to.
     */
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
    
    /**
     * Get the role definition for the user (if using a Roles table lookup by name).
     */
    public function roleDefinition()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /*
    |--------------------------------------------------------------------------
    | University Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Relationship for students: Enrollments in classes.
     */
    public function enrollments()
    {
        return $this->hasMany(\App\Modules\Academic\Models\Enrollment::class, 'student_id');
    }

    /**
     * Relationship for students: Classes they are enrolled in.
     */
    public function classes()
    {
        if ($this->isTeacher()) {
            return $this->hasMany(\App\Modules\Academic\Models\ClassModel::class, 'teacher_id');
        }
        return $this->belongsToMany(\App\Modules\Academic\Models\ClassModel::class, 'enrollments', 'student_id', 'class_id')
            ->withPivot('status', 'grade_points', 'grade_letter')
            ->withTimestamps();
    }

    /**
     * Relationship for teachers: Classes they teach.
     */
    public function taughtClasses()
    {
        return $this->hasMany(\App\Modules\Academic\Models\ClassModel::class, 'teacher_id');
    }

    /**
     * Relationship for students: Their attendance records.
     */
    public function attendances()
    {
        return $this->hasMany(\App\Modules\Attendance\Models\Attendance::class, 'student_id');
    }

    /**
     * Relationship for students: Their invoices/fees.
     */
    public function invoices()
    {
        return $this->hasMany(\App\Modules\Finance\Models\Invoice::class, 'student_id');
    }

    /**
     * Relationship for students: Their payment history.
     */
    public function payments()
    {
        return $this->hasMany(\App\Modules\Finance\Models\Payment::class, 'student_id');
    }

    /**
     * Relationship for all users: Their wallet/balance.
     */
    public function wallet()
    {
        return $this->hasOne(\App\Modules\Finance\Models\Wallet::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to restrict results based on the authenticated user's role.
     * Super Admin: Sees all users.
     * Campus Admin: Sees users only appearing in their campus.
     */
    public function scopeAccessibleBy($query, User $user)
    {
        if ($user->hasRole('super_admin')) {
            return $query; // No restrictions
        }

        if ($user->hasRole('campus_admin')) {
            return $query->where('campus_id', $user->campus_id);
        }

        // Default: Users can only see themselves (or extend logic for teachers)
        return $query->where('id', $user->id); 
    }

    /**
     * Scope to filter by role.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isCampusAdmin(): bool
    {
        return $this->role === 'campus_admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is an admin (Super Admin or Campus Admin).
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'campus_admin']);
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user has a specific permission via Role.
     */
    public function hasPermission($permission)
    {
        return $this->roleDefinition?->permissions->contains('name', $permission) ?? false;
    }

    /**
     * Get the role names for the user (compatibility method).
     */
    public function getRoleNames()
    {
        return collect([$this->role]);
    }
}

