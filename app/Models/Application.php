<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['user_id', 'job_circular_id', 'status'];

    // Relationship to get the Job details
    public function jobCircular()
    {
        return $this->belongsTo(JobCircular::class, 'job_circular_id');
    }

    // Relationship to get the User (Candidate) details
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
