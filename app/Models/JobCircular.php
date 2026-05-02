<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCircular extends Model
{
    protected $table = 'job_circulars';

    protected $fillable = [
    'user_id',
    'title',
    'job_type',
    'openings',
    'educations',    // MUST BE HERE
    'category',
    'skills_needed', // MUST BE HERE
    'description',
    'job_details',   // MUST BE HERE
    'company_name',
    'image'
];

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications() 
    {
        return $this->hasMany(Application::class, 'job_circular_id');
    }
}