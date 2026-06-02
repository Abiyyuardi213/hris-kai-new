<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        $pegawai = Pegawai::where('nip', $request->nip)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'NIP atau Tanggal Lahir salah.',
            ], 401);
        }

        // Hapus token lama jika perlu (opsional)
        // $pegawai->tokens()->delete();

        $token = $pegawai->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'pegawai' => $pegawai->load(['divisi', 'jabatan', 'shift', 'kantor', 'statusPegawai']),
                'token' => $token,
            ]
        ], 200);
    }

    public function profile(Request $request)
    {
        // $request->user() returns the authenticated Pegawai instance via Sanctum
        $pegawai = $request->user()->load(['divisi', 'jabatan', 'shift', 'kantor']);
        
        return response()->json([
            'success' => true,
            'data' => $pegawai
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
