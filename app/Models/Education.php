<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations'; // Forces the plural name
    protected $fillable = ['user_id', 'degree', 'institution', 'duration'];
}
