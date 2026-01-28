<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Kantor;
use App\Models\Presensi;
use App\Models\IzinPegawai;
use App\Models\Overtime;
use App\Models\Peran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_users' => User::count(),
            'total_pegawai' => Pegawai::count(),
            'total_kantor' => Kantor::count(),
            'total_peran' => Peran::count(),
            'presensi_hari_ini' => Presensi::whereDate('tanggal', $today)->count(),
            'izin_pending' => IzinPegawai::where('status', 'pending')->count(),
            'lembur_pending' => Overtime::where('status', 'pending')->count(),
        ];

        $recent_presensi = Presensi::with('pegawai')
            ->whereDate('tanggal', $today)
            ->orderBy('jam_masuk', 'desc')
            ->take(5)
            ->get();

        $recent_izin = IzinPegawai::with('pegawai')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recent_presensi', 'recent_izin'));
    }
}
