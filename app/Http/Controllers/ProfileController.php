<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Pegawai;

class ProfileController extends Controller
{
    public function edit()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $guard = 'web';
        } else {
            $user = Auth::guard('employee')->user();
            $guard = 'employee';
        }

        return view('profile.edit', compact('user', 'guard'));
    }

    public function update(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $guard = 'web';
            $table = 'users';
        } else {
            /** @var \App\Models\Pegawai $user */
            $user = Auth::guard('employee')->user();
            $guard = 'employee';
            $table = 'pegawais';
        }

        $rules = [
            'name' => $guard === 'web' ? 'required|string|max:255' : 'nullable',
            'nama_lengkap' => $guard === 'employee' ? 'required|string|max:255' : 'nullable',
            'email' => $guard === 'web' ? 'required|email|unique:users,email,' . $user->id : 'nullable',
            'email_pribadi' => $guard === 'employee' ? 'nullable|email|max:255' : 'nullable',
            'no_hp' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
            'foto' => 'nullable|image|max:2048',
        ];

        $validated = $request->validate($rules);

        $data = [];
        if ($guard === 'web') {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
        } else {
            $data['nama_lengkap'] = $request->nama_lengkap;
            $data['email_pribadi'] = $request->email_pribadi;
        }

        $data['no_hp'] = $request->no_hp;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('profiles', 'public');
            $data['foto'] = $path;
        }

        /** @var \Illuminate\Database\Eloquent\Model $user */
        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
