<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutedAgency extends Model
{
    protected $fillable = ['user_id', 'agency_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }
}
