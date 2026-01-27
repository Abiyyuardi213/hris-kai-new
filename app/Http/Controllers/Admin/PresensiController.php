<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
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

        return view('admin.presensi.index', compact('presensis'));
    }

    public function show($id)
    {
        $presensi = Presensi::with(['pegawai', 'shift'])->findOrFail($id);
        return view('admin.presensi.show', compact('presensi'));
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
}
