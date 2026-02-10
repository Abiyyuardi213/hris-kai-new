<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    protected $table = 'users';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;
    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'employee';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'role_id',
        'kantor_id', // Added
        'status',
    ];

    public function role()
    {
        return $this->belongsTo(Peran::class);
    }

    public function office()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }

    public function employee()
    {
        return $this->hasOne(Pegawai::class);
    }

    public function hasPermission($permission)
    {
        if (!$this->role) return false;
        return $this->role->permissions()->where('name', $permission)->exists();
    }

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
}
