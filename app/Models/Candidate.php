<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Candidate extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'identity_number',
        'name',
        'photo',
        'email',
        'password',
        'phone',
        'resume',
        'address',
        'last_education',
        'date_of_birth',
        'place_of_birth',
        'religion',
        'gender',
        'marital_status',
        'nationality',
        'npwp',
        'social_media',
        'province',
        'city',
        'district',
        'village',
        'role_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'social_media' => 'array',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Peran::class, 'role_id');
    }

    public function educations()
    {
        return $this->hasMany(CandidateEducation::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function documents()
    {
        return $this->hasMany(CandidateDocument::class);
    }
}
