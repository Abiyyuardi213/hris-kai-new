<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $saved_email = Cookie::get('saved_email');
        return view('auth.login', compact('saved_email'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = true; // Only active users can login

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->boolean('remember')) {
                Cookie::queue('saved_email', $request->email, 43200); // 30 days
            } else {
                Cookie::queue(Cookie::forget('saved_email'));
            }

            return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah, atau akun tidak aktif.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
