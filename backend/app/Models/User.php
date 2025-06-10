<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
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
        'type' => 'string'
    ];

    /**
     * Check if the user is a job seeker.
     *
     * @return bool
     */
    public function isJobSeeker(): bool
    {
        return $this->type === 'job_seeker';
    }

    /**
     * Check if the user is an employer.
     *
     * @return bool
     */
    public function isEmployer(): bool
    {
        return $this->type === 'employer';
    }

    /**
     * Get the company associated with the employer.
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get the job applications for the job seeker.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }
}
