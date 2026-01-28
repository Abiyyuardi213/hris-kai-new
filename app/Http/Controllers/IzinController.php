<?php

namespace App\Http\Controllers;

use App\Models\IzinPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $izins = IzinPegawai::where('pegawai_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('employee.izin.index', compact('izins'));
    }

    public function create()
    {
        return view('employee.izin.create');
    }

    public function store(Request $request)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'type' => 'required|in:izin,sakit,cuti,dispensasi,lainnya',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('izin_attachments', 'public');
            $validated['attachment'] = $path;
        }

        $validated['pegawai_id'] = $employee->id;
        $validated['status'] = 'pending';

        $izin = IzinPegawai::create($validated);

        // Notify Admins
        $admins = \App\Models\User::all(); // You might want to filter this by role later
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SystemNotification([
                'title' => 'Pengajuan Izin Baru',
                'message' => $employee->nama_lengkap . ' mengajukan ' . $request->type,
                'url' => route('admin.izin.index'),
                'type' => 'info',
                'icon' => 'file-text'
            ]));
        }

        return redirect()->route('employee.izin.index')->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan Admin.');
    }

    public function show(IzinPegawai $izin)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        if ($izin->pegawai_id !== $employee->id) {
            abort(403);
        }

        return view('employee.izin.show', compact('izin'));
    }
}
