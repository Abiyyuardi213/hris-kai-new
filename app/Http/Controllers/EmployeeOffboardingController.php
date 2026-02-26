<?php

namespace App\Http\Controllers;

use App\Models\Offboarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeOffboardingController extends Controller
{
    public function index()
    {
        $pegawaiId = Auth::guard('employee')->user()->id;
        $offboardings = Offboarding::where('pegawai_id', $pegawaiId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.offboardings.index', compact('offboardings'));
    }

    public function create()
    {
        $pegawaiId = Auth::guard('employee')->user()->id;

        // Cek jika sudah pernah merequest dan belum ada keputusan akhir.
        // Boleh ditambah logic di sini
        $hasPending = Offboarding::where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->exists();

        if ($hasPending) {
            return redirect()->route('employee.offboardings.index')->with('error', 'Anda masih memiliki pengajuan offboarding yang sedang diproses.');
        }

        return view('employee.offboardings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_offboarding' => 'required|string',
            'tanggal_efektif' => 'required|date|after_or_equal:today',
            'alasan_keluar' => 'required|string|max:1000',
            'saran_masukan' => 'required|string|max:1000',
        ]);

        Offboarding::create([
            'pegawai_id' => Auth::guard('employee')->user()->id,
            'tipe_offboarding' => $request->tipe_offboarding,
            'tanggal_efektif' => $request->tanggal_efektif,
            'alasan_keluar' => $request->alasan_keluar,
            'saran_masukan' => $request->saran_masukan,
            'status' => 'Pending',
        ]);

        return redirect()->route('employee.offboardings.index')
            ->with('success', 'Pengajuan Offboarding (Resign/Pensiun) Anda berhasil dikirim dan sedang menunggu tinjauan HR.');
    }

    public function show($id)
    {
        $pegawaiId = Auth::guard('employee')->user()->id;
        $offboarding = Offboarding::where('id', $id)
            ->where('pegawai_id', $pegawaiId)
            ->firstOrFail();

        return view('employee.offboardings.show', compact('offboarding'));
    }
}
