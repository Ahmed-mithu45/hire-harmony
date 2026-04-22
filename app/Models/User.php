<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'role',          // Added for Admin support
        'unique_id',
        'dob',
        'address',
        'phone',
        'title',
        'summary',
        'skills',
        'cv_path',
        'profile_photo',
        'cover_photo',   // Added for Company Profile support
        'github_url',
        'linkedin_url',
        'portfolio_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELATIONSHIPS ---

    /**
     * Get all work experiences for the user.
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get all education records for the user.
     */
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Get all applications submitted by the user.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
