<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        $currentUser = auth()->user();
        if ($currentUser && !in_array($currentUser->role->role_name ?? '', ['Super Admin', 'Administrator', 'Admin'])) {
            $officeId = $currentUser->kantor_id;
            if (!$officeId && $currentUser->employee) {
                $officeId = $currentUser->employee->kantor_id;
            }

            if ($officeId) {
                $query->where('kantor_id', $officeId);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Peran::where('role_status', true)->get();
        $offices = \App\Models\Kantor::withoutGlobalScope('office_access')->orderBy('office_name')->get();

        return view('users.index', compact('users', 'roles', 'offices'));
    }

    public function create()
    {
        $roles = Peran::where('role_status', true)->get();
        // Fetch offices without global scope to ensure Super Admin can see all offices even if there is some scope logic elsewhere
        $offices = \App\Models\Kantor::withoutGlobalScope('office_access')->orderBy('office_name')->get();
        return view('users.create', compact('roles', 'offices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:role,id',
            'kantor_id' => 'nullable|exists:offices,id', // Validate kantor_id
            'cropped_foto' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['status'] = true;

        if ($request->filled('cropped_foto')) {
            $imageData = $request->cropped_foto;
            $fileName = 'profile-photos/' . uniqid() . '.jpg';

            // Remove 'data:image/jpeg;base64,' part
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);

            Storage::disk('public')->put($fileName, base64_decode($imageData));
            $data['foto'] = $fileName;
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Peran::where('role_status', true)->get();
        $offices = \App\Models\Kantor::withoutGlobalScope('office_access')->orderBy('office_name')->get();
        return view('users.edit', compact('user', 'roles', 'offices'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:role,id',
            'kantor_id' => 'nullable|exists:offices,id', // Validate kantor_id
            'cropped_foto' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = $request->except(['password', 'foto']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->filled('cropped_foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $imageData = $request->cropped_foto;
            $fileName = 'profile-photos/' . uniqid() . '.jpg';

            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);

            Storage::disk('public')->put($fileName, base64_decode($imageData));
            $data['foto'] = $fileName;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
