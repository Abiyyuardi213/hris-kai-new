<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();
        
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
        }
        
        $pegawais = $query->paginate(15)->withQueryString();
        
        return view('admin.presensi_pegawai.index', compact('pegawais'));
    }

    public function show($id, Request $request)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        
        $date = Carbon::createFromDate($year, $month, 1);
        
        $presensis = Presensi::where('pegawai_id', $id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });
            
        $totalHadir = $presensis->where('status', 'Hadir')->count();
        $totalIzin = $presensis->where('status', 'Izin')->count();
        $totalSakit = $presensis->where('status', 'Sakit')->count();
        $totalAlpa = $presensis->where('status', 'Alpa')->count();
        
        return view('admin.presensi_pegawai.show', compact('pegawai', 'date', 'presensis', 'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpa'));
    }
}
