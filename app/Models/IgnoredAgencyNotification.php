<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IgnoredAgencyNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_id',
    ];

    /**
     * The user who ignored the agency's notifications.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The agency whose notifications are ignored.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
