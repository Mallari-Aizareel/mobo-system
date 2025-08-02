<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyRepresentative extends Model
{
   use HasFactory;

    protected $fillable = [
        'agency_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'address_id',
    ];

    // Relationships
    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

}
