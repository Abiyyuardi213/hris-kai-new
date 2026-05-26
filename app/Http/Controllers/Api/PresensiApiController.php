<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiApiController extends Controller
{
    public function history(Request $request)
    {
        $pegawai = $request->user();
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $presensis = Presensi::where('pegawai_id', $pegawai->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $presensis
        ], 200);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            // 'foto' => 'required|image' // bisa diaktifkan jika aplikasi siap kirim file
        ]);

        $pegawai = $request->user();
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

        // Simpan data
        $presensi = Presensi::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'status' => 'Hadir',
            'terlambat' => 0, // Hitung logika terlambat jika ada shift
            'keterangan' => 'Absen Mobile App'
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

        $presensi->update([
            'jam_pulang' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'pulang_cepat' => 0 // Hitung logika jika ada shift
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil!',
            'data' => $presensi
        ], 200);
    }
}
