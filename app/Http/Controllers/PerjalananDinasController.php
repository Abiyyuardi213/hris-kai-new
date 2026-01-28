<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasPeserta;
use App\Models\Pegawai;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerjalananDinasController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();

        // Trips where employee is either the requester (pemohon) or a participant (peserta)
        $trips = PerjalananDinas::with(['pemohon', 'pengetuju'])
            ->where(function ($query) use ($employee) {
                $query->where('pegawai_id', $employee->id)
                    ->orWhereHas('peserta', function ($q) use ($employee) {
                        $q->where('pegawai_id', $employee->id);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.perjalanan_dinas.index', compact('trips'));
    }

    public function create()
    {
        return view('employee.perjalanan_dinas.create');
    }

    public function store(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'tujuan' => 'required|string',
            'keperluan' => 'required|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_transportasi' => 'nullable|string',
            'estimasi_biaya' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $trip = PerjalananDinas::create([
                'pegawai_id' => $employee->id,
                'tujuan' => $validated['tujuan'],
                'keperluan' => $validated['keperluan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'jenis_transportasi' => $validated['jenis_transportasi'],
                'estimasi_biaya' => $validated['estimasi_biaya'],
                'status' => 'Pengajuan',
            ]);

            // Add self as peserta
            PerjalananDinasPeserta::create([
                'perjalanan_dinas_id' => $trip->id,
                'pegawai_id' => $employee->id,
            ]);

            // Notify Admins
            $admins = User::whereHas('role', function ($q) {
                $q->where('role_name', 'Admin'); // Or check permissions
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new SystemNotification([
                    'title' => 'Pengajuan Perjalanan Dinas Baru',
                    'message' => "{$employee->nama_lengkap} mengajukan perjalanan dinas ke {$trip->tujuan}",
                    'url' => route('admin.perjalanan_dinas.index'),
                    'type' => 'info',
                    'icon' => 'briefcase',
                ]));
            }

            DB::commit();
            return redirect()->route('employee.perjalanan_dinas.index')->with('success', 'Pengajuan perjalanan dinas berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $employee = Auth::guard('employee')->user();

        $trip = PerjalananDinas::with(['pemohon', 'pegawaiPeserta', 'pengetuju'])
            ->where(function ($query) use ($employee) {
                $query->where('pegawai_id', $employee->id)
                    ->orWhereHas('peserta', function ($q) use ($employee) {
                        $q->where('pegawai_id', $employee->id);
                    });
            })
            ->findOrFail($id);

        return view('employee.perjalanan_dinas.show', compact('trip'));
    }
}
