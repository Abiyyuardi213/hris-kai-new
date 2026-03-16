<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CandidateAuthController extends Controller
{
    public function showRegistrationForm()
    {
        $captcha = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        Session::put('captcha_code', $captcha);
        return view('recruitment.register', compact('captcha'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'identity_number' => 'required|string|max:20|unique:candidates',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:candidates',
            'password' => 'required|string|min:8|confirmed',
            'verification_code' => 'required'
        ], [
            'identity_number.unique' => 'Nomor Identitas sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Validate dynamic captcha from session
        if ($request->verification_code !== Session::get('captcha_code')) {
             return back()->withErrors(['verification_code' => 'Kode verifikasi salah.'])->withInput();
        }

        // Clear captcha after use
        Session::forget('captcha_code');

        $candidateRole = Peran::where('role_name', 'Candidate')->first();

        $candidate = Candidate::create([
            'identity_number' => $request->identity_number,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $candidateRole ? $candidateRole->id : null,
        ]);

        Auth::guard('candidate')->login($candidate);

        return redirect()->route('candidate.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function showLoginForm()
    {
        return view('recruitment.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('candidate')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('candidate.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('candidate')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/rekrutmen/login');
    }

    public function dashboard()
    {
        return view('recruitment.dashboard');
    }
}
