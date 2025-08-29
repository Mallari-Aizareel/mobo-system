<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'training_center_id', 'course_id'];

    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainees()
    {
        return $this->belongsToMany(User::class, 'enrolled_trainees', 'room_id', 'user_id');
    }

    public function modules()
    {
        return $this->hasMany(\App\Models\RoomModule::class);
    }

}