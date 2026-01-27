<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peran;
use App\Models\Kota;
use App\Models\Kantor;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\StatusPegawai;
use App\Models\Pegawai;
use App\Models\ShiftKerja;
use App\Models\ShiftPegawai;
use App\Models\MutasiPegawai;
use App\Models\HariLibur;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    public function index()
    {
        // Get counts
        $usersCount = User::count();
        $rolesCount = Peran::count();
        $citiesCount = Kota::count();

        // Get latest additions
        $latestUsers = User::latest()->take(5)->get();
        $activeRoles = Peran::where('role_status', true)->count();

        // Cities breakdown (top 5 provinces by city count)
        $topProvinces = Kota::select('province_name', DB::raw('count(*) as total'))
            ->groupBy('province_name')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('admin.master_data.index', compact(
            'usersCount',
            'rolesCount',
            'citiesCount',
            'latestUsers',
            'activeRoles',
            'topProvinces'
        ));
    }

    public function masterOffice()
    {
        // Get counts
        $officesCount = Kantor::count();
        $divisionsCount = Divisi::count();
        $positionsCount = Jabatan::count();
        $statusesCount = StatusPegawai::count();

        // Latest offices
        $latestOffices = Kantor::with('city')->latest()->take(5)->get();

        // Divisions with position count
        $divisionStats = Divisi::withCount('positions')->orderByDesc('positions_count')->take(5)->get();

        return view('admin.master_office.index', compact(
            'officesCount',
            'divisionsCount',
            'positionsCount',
            'statusesCount',
            'latestOffices',
            'divisionStats'
        ));
    }

    public function masterEmployee()
    {
        // Counts
        $employeeCount = Pegawai::count();
        $shiftCount = ShiftKerja::count();
        $activeShiftsCount = ShiftPegawai::where('end_date', '>=', now())
            ->orWhereNull('end_date')
            ->count();
        $mutationCount = MutasiPegawai::count();
        $holidayCount = HariLibur::count();

        // Gender breakdown
        $genderStats = Pegawai::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get();

        // Latest mutations
        $latestMutations = MutasiPegawai::with(['employee', 'fromPosition', 'toPosition'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming holidays
        $upcomingHolidays = HariLibur::where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        // Employees by division
        $divisionStats = Divisi::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(5)
            ->get();

        return view('admin.master_employee.index', compact(
            'employeeCount',
            'shiftCount',
            'activeShiftsCount',
            'mutationCount',
            'holidayCount',
            'genderStats',
            'latestMutations',
            'upcomingHolidays',
            'divisionStats'
        ));
    }
}
