<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['user_id', 'job_circular_id', 'status', 'interview_time'];

    public function jobCircular()
    {
        return $this->belongsTo(JobCircular::class, 'job_circular_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}