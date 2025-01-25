<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['title', 'description', 'location', 'company', 'tags', 'salary_from', 'salary_to', 'posted_by_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'posted_by_user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}
