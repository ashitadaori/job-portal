<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'designation',
        'image',
        'role',
        'skills',
        'education',
        'experience_years',
        'bio',
        'address',
        'is_verified',
        'verification_document',
        'preferred_job_types',
        'preferred_categories',
        'preferred_location',
        'preferred_salary_range',
        'phone',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'skills' => 'array',
        'education' => 'array',
        'preferred_job_types' => 'array',
        'preferred_categories' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the jobs posted by the user.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    /**
     * Get the job applications submitted by the user.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the jobs saved by the user.
     */
    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class);
    }

    // Profile relationships
    public function jobSeekerProfile(): HasOne
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    public function employerProfile(): HasOne
    {
        return $this->hasOne(EmployerProfile::class);
    }

    public function kycDocuments(): HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    // Role and Permission relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole($roleName)
    {
        // Check both the role column and the roles relationship
        return $this->role === strtolower($roleName) || 
               $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission($permissionName)
    {
        return $this->roles()
            ->whereHas('permissions', function($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })->exists();
    }

    // Role checks using the simple role column
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin') || $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->role === 'admin' || $this->isSuperAdmin();
    }

    public function isEmployer(): bool
    {
        return $this->hasRole('employer') || $this->role === 'employer';
    }

    public function isJobSeeker(): bool
    {
        return $this->hasRole('jobseeker') || $this->role === 'jobseeker';
    }

    // Messaging
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Notifications
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Audit logs
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
