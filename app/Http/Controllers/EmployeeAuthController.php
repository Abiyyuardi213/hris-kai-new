<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }
        return view('auth.employee-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        $pegawai = Pegawai::where('nip', $request->nip)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if ($pegawai) {
            Auth::guard('employee')->login($pegawai, $request->remember);
            $request->session()->regenerate();

            return redirect()->intended(route('employee.dashboard'))->with('success', 'Selamat datang kembali, ' . $pegawai->nama_lengkap . '!');
        }

        return back()->withErrors([
            'nip' => 'NIP atau Tanggal Lahir tidak sesuai.',
        ])->onlyInput('nip');
    }

    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.login')->with('success', 'Berhasil logout!');
    }

    public function dashboard()
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();
        $employee->load(['jabatan', 'divisi', 'kantor', 'statusPegawai', 'shift']);

        $announcements = \App\Models\Announcement::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('employee.dashboard', compact('employee', 'announcements'));
    }
}
