<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'module_path',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function answers()
    {
        return $this->hasMany(ModuleAnswer::class, 'module_id');
    }

}
