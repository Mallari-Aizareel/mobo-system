<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    protected $fillable = [
        'name',
        'tc_phone_number',
        'tc_email',
        'address',
        'representative',
        'r_phone_number',
        'r_email',
    ];
}
