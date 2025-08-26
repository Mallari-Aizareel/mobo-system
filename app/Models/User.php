<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AgencyRepresentative;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'password',
        'profile_picture',
        'background_picture',
        'role_id',
        'description',
        'religion',
        'birthdate',
        'address_id',
        'gender_id',
        'phone_number'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function adminlte_image()
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : asset('storage/profile_pictures/default.jpg'); 
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function hasRole($role)
    {
        $roles = [
            'admin' => 1,
            'tesda' => 2,
            'agency' => 3,
        ];

        return isset($roles[$role]) && $this->role_id == $roles[$role];
    }

    public function agencyRepresentatives()
    {
        return $this->hasMany(AgencyRepresentative::class, 'agency_id');
    }


    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function role()
{
    return $this->belongsTo(Role::class, 'role_id');
}

public function feedback()
{
    return $this->hasMany(\App\Models\AgencyFeedback::class, 'agency_id');
}

/**
 * Total likes across all users for this agency
 */
public function getLikesCountAttribute()
{
    return $this->feedback()->where('liked', true)->count();
}

/**
 * Average rating across all users for this agency
 */
public function getAverageRatingAttribute()
{
    return round($this->feedback()->whereNotNull('rating')->avg('rating') ?? 0, 1);
}

/**
 * Logged-in user rating for this agency
 */
public function myRating()
{
    return $this->feedback()
                ->where('user_id', auth()->id())
                ->value('rating');
}

/**
 * Check if specific user liked the agency
 */
public function isLikedByUser($userId)
{
    return $this->feedback()
                ->where('user_id', $userId)
                ->where('liked', true)
                ->exists();
}


public function jobPosts()
{
    return $this->hasMany(JobPost::class, 'agency_id'); // agency_id in job_posts table
}


}
