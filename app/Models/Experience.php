<?php

namespace App\Models; // Check this line carefully

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    // Ensure you have fillable properties so the create() method works
    protected $fillable = ['user_id', 'title', 'company', 'duration', 'description'];
}
