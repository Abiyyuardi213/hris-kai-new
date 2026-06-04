<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\ShiftPegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PresensiApiController extends Controller
{
    public function today(Request $request)
    {
        $pegawai = $request->user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        return response()->json([
            'success' => true,
            'data' => [
                'attendance' => Presensi::with('shift')
                    ->where('pegawai_id', $pegawai->id)
                    ->whereDate('tanggal', $today)
                    ->first(),
                'shift' => $this->getTodayShift($pegawai),
            ],
        ], 200);
    }

    public function history(Request $request)
    {
        $pegawai = $request->user();
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $presensis = Presensi::with('shift')
            ->where('pegawai_id', $pegawai->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'records' => $presensis,
                'stats' => [
                    'total_hadir' => $presensis->where('status', 'Hadir')->count(),
                    'total_late' => $presensis->where('terlambat', '>', 0)->count(),
                    'total_early' => $presensis->where('pulang_cepat', '>', 0)->count(),
                    'total_izin' => $presensis->where('status', 'Izin')->count(),
                    'total_sakit' => $presensis->where('status', 'Sakit')->count(),
                ],
            ],
        ], 200);
    }

    public function checkIn(Request $request)
    {
        $pegawai = $request->user();
        $shift = $this->getTodayShift($pegawai);

        if (!$shift) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki shift kerja. Hubungi Admin.',
            ], 422);
        }

        $isRemote = $this->isRemoteShift($shift);

        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'required_without_all:latitude,longitude|string|nullable',
            'image' => $isRemote || $shift->require_qr ? 'nullable|string' : 'required|string',
            'qr_content' => !$isRemote && $shift->require_qr ? 'required|string' : 'nullable|string',
        ]);

        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Cek apakah sudah absen masuk
        $cek = Presensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini.'
            ], 400);
        }

        $now = Carbon::now('Asia/Jakarta');
        $shiftStart = Carbon::parse($shift->start_time, 'Asia/Jakarta');
        $startTime = Carbon::now('Asia/Jakarta')->startOfDay()->setTime($shiftStart->hour, $shiftStart->minute, $shiftStart->second);

        $terlambat = 0;
        if (!$isRemote && $now->greaterThan($startTime)) {
            $terlambat = abs($now->diffInMinutes($startTime));
        }

        $imageName = $request->filled('image') ? $this->storeBase64Image($request->image, 'attendance/in') : null;

        $presensi = Presensi::create([
            'pegawai_id' => $pegawai->id,
            'shift_kerja_id' => $shift->id,
            'tanggal' => $today,
            'jam_masuk' => $now->format('H:i:s'),
            'foto_masuk' => $imageName,
            'lokasi_masuk' => $this->locationFromRequest($request),
            'status' => 'Hadir',
            'terlambat' => $terlambat,
            'keterangan' => $isRemote ? 'Remote Check-in' : ($terlambat > 0 ? 'Terlambat ' . $terlambat . ' menit' : 'Tepat Waktu'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'data' => $presensi
        ], 200);
    }

    public function checkOut(Request $request)
    {
        $pegawai = $request->user();
        $shift = $this->getTodayShift($pegawai);
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $presensi = Presensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$presensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check-in hari ini.'
            ], 400);
        }

        if ($presensi->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-out hari ini.'
            ], 400);
        }

        if (!$shift) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki shift kerja. Hubungi Admin.',
            ], 422);
        }

        $isRemote = $this->isRemoteShift($shift);

        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'required_without_all:latitude,longitude|string|nullable',
            'image' => $isRemote || $shift->require_qr ? 'nullable|string' : 'required|string',
            'qr_content' => !$isRemote && $shift->require_qr ? 'required|string' : 'nullable|string',
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $shiftEnd = Carbon::parse($shift->end_time, 'Asia/Jakarta');
        $endTime = Carbon::now('Asia/Jakarta')->startOfDay()->setTime($shiftEnd->hour, $shiftEnd->minute, $shiftEnd->second);

        $pulangCepat = 0;
        if (!$isRemote && $now->lessThan($endTime)) {
            $pulangCepat = abs($now->diffInMinutes($endTime));
        }

        $imageName = $request->filled('image') ? $this->storeBase64Image($request->image, 'attendance/out') : null;

        $presensi->update([
            'jam_pulang' => $now->format('H:i:s'),
            'foto_pulang' => $imageName,
            'lokasi_pulang' => $this->locationFromRequest($request),
            'pulang_cepat' => $pulangCepat,
            'keterangan' => $this->checkoutKeterangan($presensi->keterangan, $isRemote),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil!',
            'data' => $presensi
        ], 200);
    }

    private function getTodayShift($pegawai)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $scheduledShift = ShiftPegawai::where('employee_id', $pegawai->id)
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereDate('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->with('shift')
            ->first();

        return $scheduledShift?->shift ?? $pegawai->shift;
    }

    private function storeBase64Image(string $image, string $directory): string
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);
        $fileName = trim($directory, '/') . '_' . uniqid('', true) . '.jpg';

        Storage::disk('public')->put($fileName, base64_decode($image));

        return $fileName;
    }

    private function locationFromRequest(Request $request): string
    {
        if ($request->filled('location')) {
            return $request->location;
        }

        return $request->latitude . ',' . $request->longitude;
    }

    private function isRemoteShift($shift): bool
    {
        return stripos($shift->name ?? '', 'remote') !== false;
    }

    private function checkoutKeterangan(?string $current, bool $isRemote): ?string
    {
        if (!$isRemote) {
            return $current;
        }

        return $current === 'Remote Check-in' ? 'Remote Check-in & Check-out' : $current;
    }
}
