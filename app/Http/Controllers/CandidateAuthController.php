<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CandidateAuthController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.dashboard');
        }
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
        if (Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.dashboard');
        }
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
            return redirect()->intended(route('candidate.dashboard'))->with('success', 'Berhasil login!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Candidate $candidate */
        $candidate = Auth::guard('candidate')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'phone' => 'required|string|max:20',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'gender' => 'nullable|in:Lelaki,Perempuan',
            'marital_status' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'npwp' => 'nullable|string|max:50',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['photo', 'identity_number', '_token']);

        Log::info('Profile Update Request:', $request->all());

        if ($request->hasFile('photo')) {
            Log::info('Photo file detected in request.');
            $path = $request->file('photo')->store('photos/candidates', 'public');
            $data['photo'] = $path;
            Log::info('Photo stored at: ' . $path);
        } else {
            Log::info('No photo file detected in request.');
        }

        // Handle social media JSON
        if ($request->has('social_media')) {
            $data['social_media'] = $request->input('social_media');
        }

        $candidate->update($data);

        return redirect()->back()->with('success', 'Biodata Anda telah berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::guard('candidate')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/rekrutmen/login')->with('success', 'Berhasil logout!');
    }

    public function dashboard()
    {
        /** @var \App\Models\Candidate $candidate */
        $candidate = Auth::guard('candidate')->user();
        $applications = $candidate->applications()->with(['jobVacancy', 'jobVacancy.detail'])->latest()->get();
        
        return view('recruitment.dashboard', compact('candidate', 'applications'));
    }

    public function profile()
    {
        return view('recruitment.profile');
    }
}
