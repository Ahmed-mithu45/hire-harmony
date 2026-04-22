<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCircular extends Model
{
    protected $table = 'job_circulars'; //

    protected $fillable = [
        'user_id', // Add this
        'title',
        'company_name',
        'description',
        'image',
        'openings',
        'job_type',
        'category'
    ];
    public function company()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function applications() {
    return $this->hasMany(Application::class);
}
}
