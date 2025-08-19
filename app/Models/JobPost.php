<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'job_position',
        'job_description',
        'job_qualifications',
        'job_benefits',
        'job_location',
        'job_salary',
        'job_schedule',
        'job_type_id',
        'job_image'
    ];

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }

    public function likes()
{
    return $this->hasMany(Like::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}
}
