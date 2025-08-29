<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'user_id',
        'answer_path',
        'status',
        'score',
    ];

    public function module()
    {
        return $this->belongsTo(RoomModule::class, 'module_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
