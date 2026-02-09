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
        $query = Pegawai::with(['statusPegawai', 'shift', 'divisi', 'jabatan', 'kantor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_pegawai_id', $request->status_id);
        }

        // Sorting logic
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('nama_lengkap', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_lengkap', 'desc');
                break;
            case 'joined_desc':
                $query->orderBy('tanggal_masuk', 'desc');
                break;
            case 'joined_asc':
                $query->orderBy('tanggal_masuk', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $employees = $query->paginate(10)->withQueryString();
        $divisions = Divisi::orderBy('name')->get();
        $offices = Kantor::orderBy('office_name')->get();
        $statuses = StatusPegawai::orderBy('name')->get();

        return view('employees.index', compact('employees', 'divisions', 'offices', 'statuses'));
    }

    public function create()
    {
        $statuses = StatusPegawai::all();
        $shifts = ShiftKerja::all();
        $divisions = Divisi::all();
        $positions = Jabatan::all();
        $offices = Kantor::all();

        // NIP Automation: Starts from 71000
        $lastPegawai = Pegawai::whereRaw("nip REGEXP '^[0-9]+$'")->orderByRaw('CAST(nip AS UNSIGNED) DESC')->first();
        $nextNip = $lastPegawai ? intval($lastPegawai->nip) + 1 : 71000;

        return view('employees.create', compact('statuses', 'shifts', 'divisions', 'positions', 'offices', 'nextNip'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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

        if ($request->filled('foto_cropped')) {
            $imageData = $request->foto_cropped;
            $fileName = 'employees/' . uniqid() . '.jpg';

            // Remove data:image/jpeg;base64,
            $base64Image = substr($imageData, strpos($imageData, ',') + 1);
            Storage::disk('public')->put($fileName, base64_decode($base64Image));
            $validated['foto'] = $fileName;
        } elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('employees', 'public');
            $validated['foto'] = $path;
        }

        Pegawai::create($validated);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(Pegawai $employee)
    {
        $statuses = StatusPegawai::all();
        $shifts = ShiftKerja::all();
        $divisions = Divisi::all();
        $positions = Jabatan::all();
        $offices = Kantor::all();

        return view('employees.edit', compact('employee', 'statuses', 'shifts', 'divisions', 'positions', 'offices'));
    }

    public function update(Request $request, Pegawai $employee)
    {
        $validated = $request->validate([
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

    public function idCard(Pegawai $employee)
    {
        return view('employees.id-card', compact('employee'));
    }

    public function idCardBack(Pegawai $employee)
    {
        return view('employees.id-card-back', compact('employee'));
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
