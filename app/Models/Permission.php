<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Permission extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'display_name', 'module'];

    public function roles()
    {
        return $this->belongsToMany(Peran::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
