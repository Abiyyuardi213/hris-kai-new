<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Peran;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'view-dashboard', 'display_name' => 'Melihat Dashboard', 'module' => 'Dashboard'],

            // Peran & Permission
            ['name' => 'manage-roles', 'display_name' => 'Manajemen Peran', 'module' => 'Settings'],

            // Users
            ['name' => 'manage-users', 'display_name' => 'Manajemen User Admin', 'module' => 'Users'],

            // Master Data (Cities, Offices, Divisions, Positions, Employment Status)
            ['name' => 'manage-master-data', 'display_name' => 'Manajemen Master Data', 'module' => 'Master Data'],

            // Employees
            ['name' => 'view-employees', 'display_name' => 'Melihat Daftar Pegawai', 'module' => 'Employees'],
            ['name' => 'manage-employees', 'display_name' => 'Kelola Data Pegawai', 'module' => 'Employees'],
            ['name' => 'manage-mutations', 'display_name' => 'Kelola Mutasi Pegawai', 'module' => 'Employees'],

            // Shifts
            ['name' => 'manage-shifts', 'display_name' => 'Kelola Shift Kerja', 'module' => 'Attendance'],

            // Attendance
            ['name' => 'view-attendance', 'display_name' => 'Lihat Presensi Pegawai', 'module' => 'Attendance'],
            ['name' => 'manage-attendance', 'display_name' => 'Kelola Data Presensi', 'module' => 'Attendance'],

            // Izin / Sakit
            ['name' => 'manage-izin', 'display_name' => 'Kelola Izin & Sakit', 'module' => 'Attendance'],

            // Lembur
            ['name' => 'manage-overtime', 'display_name' => 'Kelola Lembur', 'module' => 'Attendance'],

            // Holidays
            ['name' => 'manage-holidays', 'display_name' => 'Kelola Hari Libur', 'module' => 'Settings'],

            // Assets
            ['name' => 'manage-assets', 'display_name' => 'Manajemen Aset', 'module' => 'Assets'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }

        // Auto-assign all to Administrator
        $admin = Peran::where('role_name', 'Administrator')->orWhere('role_name', 'Admin')->first();
        if ($admin) {
            $allPermissions = Permission::all();
            $admin->permissions()->sync($allPermissions->pluck('id'));
        }
    }
}
