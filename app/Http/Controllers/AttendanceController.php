<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\ShiftKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $todayAttendance = Presensi::where('pegawai_id', $employee->id)
            ->where('tanggal', Carbon::today())
            ->first();

        $history = Presensi::where('pegawai_id', $employee->id)
            ->latest('tanggal')
            ->take(7)
            ->get();

        $shift = $employee->shift;

        return view('employee.attendance.index', compact('employee', 'todayAttendance', 'history', 'shift'));
    }

    public function clockIn(Request $request)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();
        $shift = $employee->shift;

        if (!$shift) {
            return back()->with('error', 'Anda belum memiliki shift kerja. Hubungi Admin.');
        }

        $exist = Presensi::where('pegawai_id', $employee->id)
            ->where('tanggal', Carbon::today())
            ->first();

        if ($exist) {
            return back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
        }

        $request->validate([
            'image' => 'required|string', // Base64 image
            'location' => 'required|string',
        ]);

        $now = Carbon::now();
        $startTime = Carbon::createFromFormat('H:i:s', $shift->start_time);

        $terlambat = 0;
        if ($now->greaterThan($startTime)) {
            $terlambat = $now->diffInMinutes($startTime);
        }

        // Save image
        $image = $request->image;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'attendance/in_' . uniqid() . '.jpg';
        Storage::disk('public')->put($imageName, base64_decode($image));

        Presensi::create([
            'pegawai_id' => $employee->id,
            'shift_kerja_id' => $shift->id,
            'tanggal' => Carbon::today(),
            'jam_masuk' => $now->toTimeString(),
            'foto_masuk' => $imageName,
            'lokasi_masuk' => $request->location,
            'status' => 'Hadir',
            'terlambat' => $terlambat,
        ]);

        return redirect()->route('employee.attendance.index')->with('success', 'Berhasil Absen Masuk. Semangat Bekerja!');
    }

    public function clockOut(Request $request)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();
        $shift = $employee->shift;

        $attendance = Presensi::where('pegawai_id', $employee->id)
            ->where('tanggal', Carbon::today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absensi masuk.');
        }

        if ($attendance->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
        }

        $request->validate([
            'image' => 'required|string', // Base64 image
            'location' => 'required|string',
        ]);

        $now = Carbon::now();
        $endTime = Carbon::createFromFormat('H:i:s', $shift->end_time);

        $pulangCepat = 0;
        if ($now->lessThan($endTime)) {
            $pulangCepat = $now->diffInMinutes($endTime);
        }

        // Save image
        $image = $request->image;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'attendance/out_' . uniqid() . '.jpg';
        Storage::disk('public')->put($imageName, base64_decode($image));

        $attendance->update([
            'jam_pulang' => $now->toTimeString(),
            'foto_pulang' => $imageName,
            'lokasi_pulang' => $request->location,
            'pulang_cepat' => $pulangCepat,
        ]);

        return redirect()->route('employee.attendance.index')->with('success', 'Berhasil Absen Pulang. Hati-hati di jalan!');
    }
}
