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

        // Standard history for sidebar (last 7 days)
        $history = Presensi::where('pegawai_id', $employee->id)
            ->latest('tanggal')
            ->take(7)
            ->get();

        $shift = $employee->shift;

        return view('employee.attendance.index', compact(
            'employee',
            'todayAttendance',
            'history',
            'shift'
        ));
    }

    public function history(Request $request)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $selectedMonth = $request->get('month', date('m'));
        $selectedYear = $request->get('year', date('Y'));

        // Get monthly history
        $monthlyHistory = Presensi::where('pegawai_id', $employee->id)
            ->whereMonth('tanggal', $selectedMonth)
            ->whereYear('tanggal', $selectedYear)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Calculate Stats
        $stats = [
            'total_hadir' => $monthlyHistory->where('status', 'Hadir')->count(),
            'total_late' => $monthlyHistory->where('terlambat', '>', 0)->count(),
            'total_early' => $monthlyHistory->where('pulang_cepat', '>', 0)->count(),
            'total_izin' => $monthlyHistory->where('status', 'Izin')->count(),
            'total_sakit' => $monthlyHistory->where('status', 'Sakit')->count(),
        ];

        return view('employee.attendance.history', compact(
            'employee',
            'monthlyHistory',
            'selectedMonth',
            'selectedYear',
            'stats'
        ));
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
        $shiftStart = Carbon::parse($shift->start_time);
        $startTime = Carbon::today()->setTime($shiftStart->hour, $shiftStart->minute, $shiftStart->second);

        $terlambat = 0;
        // Check if shift is NOT remote before calculating lateness
        if (stripos($shift->name, 'remote') === false && $now->greaterThan($startTime)) {
            $terlambat = abs($now->diffInMinutes($startTime));
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
            'keterangan' => $terlambat > 0 ? 'Terlambat ' . $terlambat . ' menit' : 'Tepat Waktu',
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
        $shiftEnd = Carbon::parse($shift->end_time);
        $endTime = Carbon::today()->setTime($shiftEnd->hour, $shiftEnd->minute, $shiftEnd->second);

        $pulangCepat = 0;
        // Check if shift is NOT remote before calculating early departure
        if (stripos($shift->name, 'remote') === false && $now->lessThan($endTime)) {
            $pulangCepat = abs($now->diffInMinutes($endTime));
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
