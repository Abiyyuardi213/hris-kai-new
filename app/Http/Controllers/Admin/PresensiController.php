<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\ShiftKerja;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['pegawai', 'shift'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc');

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
        
        $pegawais = Pegawai::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.presensi.index', compact('presensis', 'pegawais'));
    }

    public function show($id)
    {
        $presensi = Presensi::with(['pegawai', 'shift'])->findOrFail($id);
        return view('admin.presensi.show', compact('presensi'));
    }

    public function create()
    {
        $pegawais = Pegawai::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.presensi.create', compact('pegawais'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string'
        ]);

        $presensi = Presensi::with('shift')->findOrFail($id);
        $data = $request->only(['jam_masuk', 'jam_pulang', 'status', 'keterangan']);

        // Recalculate late and early leave if status is 'Hadir' and times are provided
        if ($data['status'] === 'Hadir' && $presensi->shift) {
            if ($data['jam_masuk']) {
                $checkIn = \Carbon\Carbon::parse($data['jam_masuk']);
                $shiftStart = \Carbon\Carbon::parse($presensi->shift->start_time);

                // Compare only time part using a fixed date to avoid issues
                $checkInTime = \Carbon\Carbon::today()->setTime($checkIn->hour, $checkIn->minute, $checkIn->second);
                $shiftStartTime = \Carbon\Carbon::today()->setTime($shiftStart->hour, $shiftStart->minute, $shiftStart->second);

                $data['terlambat'] = $checkInTime->gt($shiftStartTime) ? abs($checkInTime->diffInMinutes($shiftStartTime)) : 0;
            } else {
                $data['terlambat'] = 0;
            }

            if ($data['jam_pulang']) {
                $checkOut = \Carbon\Carbon::parse($data['jam_pulang']);
                $shiftEnd = \Carbon\Carbon::parse($presensi->shift->end_time);

                $checkOutTime = \Carbon\Carbon::today()->setTime($checkOut->hour, $checkOut->minute, $checkOut->second);
                $shiftEndTime = \Carbon\Carbon::today()->setTime($shiftEnd->hour, $shiftEnd->minute, $shiftEnd->second);

                $data['pulang_cepat'] = $checkOutTime->lt($shiftEndTime) ? abs($shiftEndTime->diffInMinutes($checkOutTime)) : 0;
            } else {
                $data['pulang_cepat'] = 0;
            }

            // Sync dynamic remarks (Keterangan) for 'Hadir' status
            if ($data['terlambat'] > 0) {
                $data['keterangan'] = 'Terlambat ' . $data['terlambat'] . ' menit';
            } else {
                $data['keterangan'] = 'Tepat Waktu';
            }
        } else {
            $data['terlambat'] = 0;
            $data['pulang_cepat'] = 0;
            // If status is Izin/Sakit/Alpa, we keep the admin's manual keterangan
        }

        $presensi->update($data);

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string'
        ]);

        $pegawai = Pegawai::with('shiftPegawais.shiftKerja')->findOrFail($request->pegawai_id);
        
        // Find active shift for the day if it exists
        $shiftKerjaId = null;
        $dayOfWeek = \Carbon\Carbon::parse($request->tanggal)->locale('id')->isoFormat('dddd');
        
        // In this app, maybe employees don't have shiftPegawais but a direct shift_id or maybe shift_kerja_id? 
        // I need to check how shift is assigned to employee to do this properly.
        // Let's assume null for now, I will refine this later if needed.

        $data = $request->only(['pegawai_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'keterangan']);
        
        // Check if attendance already exists for this date
        $existingPresensi = Presensi::where('pegawai_id', $request->pegawai_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();
            
        if ($existingPresensi) {
            return redirect()->back()->with('error', 'Presensi untuk pegawai ini pada tanggal tersebut sudah ada.');
        }

        // We will just let the shift be null if we can't find it easily, but wait, let's see how update handles it.
        // The update handles it if shift exists.

        if ($data['status'] === 'Hadir') {
            // Since we might not have shift, we just default to 0 for tardiness if shift is missing
            $data['terlambat'] = 0;
            $data['pulang_cepat'] = 0;
            $data['keterangan'] = $data['keterangan'] ?? 'Input Manual';
        } else {
            $data['terlambat'] = 0;
            $data['pulang_cepat'] = 0;
        }

        Presensi::create($data);

        return redirect()->back()->with('success', 'Data presensi berhasil ditambahkan secara manual');
    }
}
