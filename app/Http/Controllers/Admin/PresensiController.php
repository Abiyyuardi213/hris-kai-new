<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['pegawai', 'shift'])->latest('tanggal');

        // Filter by date
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Filter by employee name/nip
        if ($request->filled('search')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $presensis = $query->paginate(15)->withQueryString();

        return view('admin.presensi.index', compact('presensis'));
    }

    public function show($id)
    {
        $presensi = Presensi::with(['pegawai', 'shift'])->findOrFail($id);
        return view('admin.presensi.show', compact('presensi'));
    }
}
