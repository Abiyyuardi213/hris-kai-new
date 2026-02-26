<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offboarding;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffboardingController extends Controller
{
    public function index()
    {
        $offboardings = Offboarding::with('pegawai')->orderBy('created_at', 'desc')->get();
        return view('admin.offboardings.index', compact('offboardings'));
    }

    public function create()
    {
        // Admin bisa membuatkan offboarding (misal jika PHK / Demosi)
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        return view('admin.offboardings.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tipe_offboarding' => 'required|string',
            'tanggal_efektif' => 'required|date',
            'catatan_admin' => 'nullable|string',
        ]);

        Offboarding::create([
            'pegawai_id' => $request->pegawai_id,
            'tipe_offboarding' => $request->tipe_offboarding,
            'tanggal_efektif' => $request->tanggal_efektif,
            'status' => 'In Progress',
            'catatan_admin' => $request->catatan_admin,
            'processed_by' => Auth::id()
        ]);

        return redirect()->route('admin.offboardings.index')->with('success', 'Proses offboarding berhasil diinisiasi.');
    }

    public function show($id)
    {
        $offboarding = Offboarding::with(['pegawai', 'processor'])->findOrFail($id);
        return view('admin.offboardings.show', compact('offboarding'));
    }

    public function updateProcess(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Rejected',
            'clearance_id_card' => 'nullable',
            'clearance_laptop' => 'nullable',
            'clearance_dokumen' => 'nullable',
            'uang_pesangon' => 'nullable|numeric|min:0',
            'catatan_admin' => 'nullable|string',
        ]);

        $offboarding = Offboarding::findOrFail($id);

        $offboarding->update([
            'status' => $request->status,
            'clearance_id_card' => $request->has('clearance_id_card') ? true : false,
            'clearance_laptop' => $request->has('clearance_laptop') ? true : false,
            'clearance_dokumen' => $request->has('clearance_dokumen') ? true : false,
            'uang_pesangon' => $request->uang_pesangon ?? 0,
            'catatan_admin' => $request->catatan_admin,
            'processed_by' => Auth::id(),
        ]);

        if ($offboarding->pegawai) {
            $offboarding->pegawai->notify(new \App\Notifications\SystemNotification([
                'title' => 'Update Status Offboarding',
                'message' => 'Status proses offboarding Anda saat ini adalah: ' . $request->status,
                'url' => route('employee.offboardings.show', $offboarding->id),
                'type' => $request->status == 'Completed' ? 'success' : 'info',
                'icon' => $request->status == 'Completed' ? 'check-circle' : 'activity'
            ]));

            // Tambahan logic jika Completed: Set non-aktif pegawai
            if ($request->status == 'Completed') {
                $statusNonAktif = \App\Models\StatusPegawai::where('name', 'Non-Aktif')->orWhere('name', 'Resign')->first();
                if ($statusNonAktif) {
                    $offboarding->pegawai->update(['status_pegawai_id' => $statusNonAktif->id, 'tanggal_keluar' => $offboarding->tanggal_efektif]);
                }
            }
        }

        return redirect()->back()->with('success', 'Proses offboarding berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $offboarding = Offboarding::findOrFail($id);
        $offboarding->delete();
        return redirect()->route('admin.offboardings.index')->with('success', 'Data offboarding berhasil dihapus.');
    }
}
