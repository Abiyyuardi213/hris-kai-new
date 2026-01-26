<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peran;
use App\Models\Kota;
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
}
