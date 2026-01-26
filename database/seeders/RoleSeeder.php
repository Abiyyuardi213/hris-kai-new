<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if roles exist before creating, or use firstOrCreate
        Role::firstOrCreate(
            ['role_name' => 'Admin'],
            [
                'role_description' => 'Administrator with full access',
                'role_status' => true
            ]
        );

        Role::firstOrCreate(
            ['role_name' => 'Pegawai'],
            [
                'role_description' => 'Regular employee',
                'role_status' => true
            ]
        );
    }
}
