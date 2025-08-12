<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledTrainee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'valid_id',
        'certificate',
        'status_id',
        'room_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}