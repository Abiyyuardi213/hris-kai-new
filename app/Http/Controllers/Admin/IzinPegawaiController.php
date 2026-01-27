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

        IzinPegawai::create($validated);

        return redirect()->route('admin.izin.index')->with('success', 'Izin pegawai berhasil ditambahkan');
    }

    public function updateStatus(Request $request, IzinPegawai $izin)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $izin->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Status pengajuan izin berhasil diperbarui');
    }

    public function destroy(IzinPegawai $izin)
    {
        if ($izin->attachment) {
            Storage::disk('public')->delete($izin->attachment);
        }
        $izin->delete();
        return back()->with('success', 'Data izin berhasil dihapus');
    }
}
