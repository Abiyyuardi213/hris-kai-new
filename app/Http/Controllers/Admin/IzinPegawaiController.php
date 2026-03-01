<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinPegawai;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = IzinPegawai::with('pegawai');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $izins = $query->latest()->paginate(10);
        $employees = Pegawai::orderBy('nama_lengkap')->get();

        return view('admin.izin.index', compact('izins', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'type' => 'required|in:izin,sakit,cuti,dispensasi,lainnya',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('izin_attachments', 'public');
            $validated['attachment'] = $path;
        }

        $validated['status'] = 'approved'; // Admin filing is auto-approved usually
        $validated['approved_by'] = Auth::id();
        $validated['admin_note'] = 'Diinput oleh Admin';

        // Check and deduct leave balance if type is cuti
        if ($validated['type'] === 'cuti') {
            $start = \Carbon\Carbon::parse($validated['start_date']);
            $end = \Carbon\Carbon::parse($validated['end_date']);
            $days = $start->diffInDays($end) + 1;

            $pegawai = Pegawai::findOrFail($validated['pegawai_id']);
            if ($pegawai->sisa_cuti < $days) {
                return back()->with('error', 'Sisa cuti pegawai tidak mencukupi (Sisa: ' . $pegawai->sisa_cuti . ', Pengajuan: ' . $days . ')')->withInput();
            }

            $pegawai->decrement('sisa_cuti', $days);
        }

        IzinPegawai::create($validated);

        return redirect()->route('admin.izin.index')->with('success', 'Izin pegawai berhasil ditambahkan');
    }

    public function updateStatus(Request $request, IzinPegawai $izin)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        // Deduct leave balance if type is cuti and status changes to approved
        if ($request->status === 'approved' && $izin->status !== 'approved') {
            if ($izin->type === 'cuti') {
                $start = \Carbon\Carbon::parse($izin->start_date);
                $end = \Carbon\Carbon::parse($izin->end_date);
                $days = $start->diffInDays($end) + 1;

                $pegawai = $izin->pegawai;
                if ($pegawai->sisa_cuti < $days) {
                    return back()->with('error', 'Sisa cuti pegawai tidak mencukupi (Sisa: ' . $pegawai->sisa_cuti . ', Pengajuan: ' . $days . ')');
                }

                $pegawai->decrement('sisa_cuti', $days);
            }
        }

        $izin->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'admin_note' => $request->admin_note,
        ]);

        // Notify Employee
        $izin->pegawai->notify(new \App\Notifications\SystemNotification([
            'title' => 'Status Pengajuan Izin',
            'message' => 'Pengajuan ' . $izin->type . ' Anda telah ' . ($request->status == 'approved' ? 'Disetujui' : 'Ditolak'),
            'url' => route('employee.izin.index'),
            'type' => $request->status == 'approved' ? 'success' : 'danger',
            'icon' => $request->status == 'approved' ? 'check-circle' : 'x-circle'
        ]));

        return back()->with('success', 'Status pengajuan izin berhasil diperbarui');
    }

    public function print($id)
    {
        $izin = IzinPegawai::with(['pegawai.jabatan', 'pegawai.divisi'])->findOrFail($id);

        if ($izin->status !== 'approved') {
            return back()->with('error', 'Surat izin hanya dapat dicetak setelah disetujui.');
        }

        $mdFinance = Pegawai::whereHas('jabatan', function ($query) {
            $query->where('name', 'Managing Director of Finance');
        })->first();

        // Fallback
        if (!$mdFinance) {
            $mdFinance = (object)[
                'nama_lengkap' => 'INDARTO PAMOENGKAS',
                'nip' => '654324'
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.izin.pdf', compact('izin', 'mdFinance'));
        return $pdf->stream('surat-izin-' . $izin->pegawai->nip . '.pdf');
    }

    public function destroy(IzinPegawai $izin)
    {
        // Restore leave balance if deleting an approved cuti
        if ($izin->status === 'approved' && $izin->type === 'cuti') {
            $start = \Carbon\Carbon::parse($izin->start_date);
            $end = \Carbon\Carbon::parse($izin->end_date);
            $days = $start->diffInDays($end) + 1;

            $izin->pegawai->increment('sisa_cuti', $days);
        }

        if ($izin->attachment) {
            Storage::disk('public')->delete($izin->attachment);
        }
        $izin->delete();
        return back()->with('success', 'Data izin berhasil dihapus dan jatah cuti dikembalikan (jika ada)');
    }
}
