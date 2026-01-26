<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Peran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ensure roles exist
        $adminRole = Peran::where('role_name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Peran::create([
                'role_name' => 'Admin',
                'role_description' => 'Administrator with full access',
                'role_status' => true
            ]);
        }

        $pegawaiRole = Peran::where('role_name', 'Pegawai')->first();
        if (!$pegawaiRole) {
            $pegawaiRole = Peran::create([
                'role_name' => 'Pegawai',
                'role_description' => 'Regular employee',
                'role_status' => true
            ]);
        }

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'role_id' => $adminRole->id,
            'status' => true,
        ]);

        User::create([
            'name' => 'Pegawai User',
            'email' => 'pegawai@gmail.com',
            'password' => 'password',
            'role_id' => $pegawaiRole->id,
            'status' => true,
        ]);
    }
}
