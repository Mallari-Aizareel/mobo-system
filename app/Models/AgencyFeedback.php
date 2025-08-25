<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyFeedback extends Model
{
    protected $table = 'agency_feedback';

    protected $fillable = [
        'agency_id',
        'user_id',
        'liked',
        'rating'
    ];

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

