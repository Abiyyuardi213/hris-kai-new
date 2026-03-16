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
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Peran::class, 'role_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
