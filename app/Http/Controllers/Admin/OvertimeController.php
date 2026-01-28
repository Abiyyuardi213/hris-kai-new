<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Overtime::with('pegawai');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $overtimes = $query->latest()->paginate(10);
        $employees = Pegawai::orderBy('nama_lengkap')->get();

        return view('admin.overtime.index', compact('overtimes', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string',
        ]);

        $validated['status'] = 'approved'; // Admin assignment is automatically approved
        $validated['type'] = 'assignment';
        $validated['approved_by'] = Auth::id();
        $validated['admin_note'] = 'Penugasan langsung dari Admin';

        $overtime = Overtime::create($validated);

        // Notify Employee
        $overtime->pegawai->notify(new \App\Notifications\SystemNotification([
            'title' => 'Penugasan Lembur Baru',
            'message' => 'Anda mendapatkan penugasan lembur untuk tanggal ' . $overtime->date,
            'url' => route('employee.overtime.index'),
            'type' => 'info',
            'icon' => 'clock'
        ]));

        return redirect()->route('admin.overtime.index')->with('success', 'Penugasan lembur berhasil dibuat.');
    }

    public function updateStatus(Request $request, Overtime $overtime)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $overtime->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'admin_note' => $request->admin_note,
        ]);

        // Notify Employee
        $overtime->pegawai->notify(new \App\Notifications\SystemNotification([
            'title' => 'Status Pengajuan Lembur',
            'message' => 'Pengajuan lembur Anda untuk tanggal ' . $overtime->date . ' telah ' . ($request->status == 'approved' ? 'Disetujui' : 'Ditolak'),
            'url' => route('employee.overtime.index'),
            'type' => $request->status == 'approved' ? 'success' : 'danger',
            'icon' => $request->status == 'approved' ? 'check-circle' : 'x-circle'
        ]));

        return back()->with('success', 'Status lembur berhasil diperbarui.');
    }

    public function destroy(Overtime $overtime)
    {
        $overtime->delete();
        return back()->with('success', 'Data lembur berhasil dihapus.');
    }
}
