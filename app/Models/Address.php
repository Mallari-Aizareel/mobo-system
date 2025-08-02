<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'barangay',
        'city',
        'province',
        'country',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
