<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'zip_code',
        'summary',
        'school_name',
        'degree',
        'field_of_study',
        'grad_year',
        'company_name',
        'job_title',
        'job_start_date',
        'job_end_date',
        'job_description',
        'skills',
        'certification_name',
        'certification_year',
        'pdf_path',
        'parsed_skills',
        'parsed_courses',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
