<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_name',
        'contact_no',
        'email_address',
        'address',
        'representative_full_name',
        'representative_address',
        'representative_contact_no',
        'representative_email_address',
        'agency_description',
        'agency_logo',
        'agency_cover_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

