<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $table = 'role';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'role_name',
        'role_description',
        'role_status',
    ];

    protected static function booted()
    {
        static::creating(function ($role) {
            if (!$role->id) {
                $role->id = (string) Str::uuid();
            }
        });

        // static::created(function ($role) {
        //     RiwayatAktivitasLog::add(
        //         'role', 'create', "Membuat role {$role->role_name}",
        //         optional(Auth::user())->id
        //     );
        // });

        // static::updated(function ($role) {
        //     RiwayatAktivitasLog::add(
        //         'role', 'update', "Memperbarui role {$role->role_name}",
        //         optional(Auth::user())->id
        //     );
        // });

        // static::deleted(function ($role) {
        //     RiwayatAktivitasLog::add(
        //         'role', 'delete', "Menghapus role {$role->role_name}",
        //         optional(Auth::user())->id
        //     );
        // });
    }

    public static function createRole($data)
    {
        return self::create([
            'role_name'        => $data['role_name'],
            'role_description' => $data['role_description'] ?? null,
            'role_status'      => $data['role_status'] ?? true,
        ]);
    }

    public function updateRole($data)
    {
        $this->update([
            'role_name'        => $data['role_name'],
            'role_description' => $data['role_description'] ?? $this->role_description,
            'role_status'      => $data['role_status'] ?? $this->role_status,
        ]);
    }

    public function deleteRole()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->role_status = !$this->role_status;
        $this->save();

        // RiwayatAktivitasLog::add(
        //     'role', 'toggle_status', "Mengubah status role {$this->role_name}",
        //     optional(Auth::user())->id
        // );
    }
}
