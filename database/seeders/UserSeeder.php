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
        $adminRole = Peran::firstOrCreate(
            ['role_name' => 'Admin'],
            [
                'role_description' => 'Administrator with full access',
                'role_status' => true
            ]
        );

        $pegawaiRole = Peran::firstOrCreate(
            ['role_name' => 'Pegawai'],
            [
                'role_description' => 'Regular employee',
                'role_status' => true
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'role_id' => $adminRole->id,
                'status' => true,
                'kantor_id' => 1,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pegawai@gmail.com'],
            [
                'name' => 'Pegawai User',
                'password' => 'password',
                'role_id' => $pegawaiRole->id,
                'status' => true,
            ]
        );
    }
}
