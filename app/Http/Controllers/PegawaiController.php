<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\StatusPegawai;
use App\Models\ShiftKerja;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['user', 'statusPegawai', 'shift', 'divisi', 'jabatan', 'kantor']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%");
        }

        $employees = $query->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $users = User::doesntHave('employee')->get(); // Only users without employee record
        $statuses = StatusPegawai::all();
        $shifts = ShiftKerja::all();
        $divisions = Divisi::all();
        $positions = Jabatan::all();
        $offices = Kantor::all();

        return view('employees.create', compact('users', 'statuses', 'shifts', 'divisions', 'positions', 'offices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'status_pegawai_id' => 'required|exists:employment_statuses,id',
            'sisa_cuti' => 'required|integer|min:0',
            'shift_kerja_id' => 'nullable|exists:shifts,id',
            'divisi_id' => 'nullable|exists:divisions,id',
            'jabatan_id' => 'nullable|exists:positions,id',
            'kantor_id' => 'nullable|exists:offices,id',
            'nip' => 'required|unique:pegawais,nip',
            'nik' => 'required|digits:16|unique:pegawais,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email_pribadi' => 'nullable|email|max:255',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('employees', 'public');
            $validated['foto'] = $path;
        }

        Pegawai::create($validated);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(Pegawai $employee)
    {
        // Allow selecting the current user or other users without employee record
        $users = User::whereDoesntHave('employee')->orWhere('id', $employee->user_id)->get();
        $statuses = StatusPegawai::all();
        $shifts = ShiftKerja::all();
        $divisions = Divisi::all();
        $positions = Jabatan::all();
        $offices = Kantor::all();

        return view('employees.edit', compact('employee', 'users', 'statuses', 'shifts', 'divisions', 'positions', 'offices'));
    }

    public function update(Request $request, Pegawai $employee)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'status_pegawai_id' => 'required|exists:employment_statuses,id',
            'sisa_cuti' => 'required|integer|min:0',
            'shift_kerja_id' => 'nullable|exists:shifts,id',
            'divisi_id' => 'nullable|exists:divisions,id',
            'jabatan_id' => 'nullable|exists:positions,id',
            'kantor_id' => 'nullable|exists:offices,id',
            'nip' => 'required|unique:pegawais,nip,' . $employee->id,
            'nik' => 'required|digits:16|unique:pegawais,nik,' . $employee->id,
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email_pribadi' => 'nullable|email|max:255',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($employee->foto) {
                Storage::disk('public')->delete($employee->foto);
            }
            $path = $request->file('foto')->store('employees', 'public');
            $validated['foto'] = $path;
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui');
    }

    public function destroy(Pegawai $employee)
    {
        if ($employee->foto) {
            Storage::disk('public')->delete($employee->foto);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus');
    }
}
