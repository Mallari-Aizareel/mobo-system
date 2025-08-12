<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_time',
        'part_time',
        'hybrid',
        'remote',
        'on_site',
        'urgent',
        'open_for_fresh_graduates'
    ];
}
