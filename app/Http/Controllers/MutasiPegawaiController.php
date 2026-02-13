<?php

namespace App\Http\Controllers;

use App\Models\MutasiPegawai;
use App\Models\Pegawai;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiPegawai::with(['employee', 'fromDivision', 'fromPosition', 'fromOffice', 'toDivision', 'toPosition', 'toOffice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('division')) {
            $query->where('to_division_id', $request->division);
        }

        if ($request->filled('office')) {
            $query->where('to_office_id', $request->office);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('mutation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('mutation_date', '<=', $request->end_date);
        }

        $mutations = $query->latest()->paginate(10);

        $divisions = Divisi::orderBy('name')->get();
        $offices = Kantor::orderBy('office_name')->get();
        $types = ['promosi', 'demosi', 'rotasi', 'mutasi'];

        return view('mutations.index', compact('mutations', 'divisions', 'offices', 'types'));
    }

    public function create()
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        // Initially pass all divisions, JS will filter them
        $divisions = Divisi::with('directorate')->orderBy('name')->get();
        $positions = Jabatan::all();
        $offices = Kantor::all();
        $directorates = \App\Models\Directorate::orderBy('name')->get();

        return view('mutations.create', compact('employees', 'divisions', 'positions', 'offices', 'directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:pegawais,id',
            'type' => 'required|in:promosi,demosi,rotasi,mutasi',
            'to_division_id' => 'nullable|exists:divisions,id',
            'to_position_id' => 'nullable|exists:positions,id',
            'to_office_id' => 'nullable|exists:offices,id',
            'mutation_date' => 'required|date',
            'reason' => 'required|string',
        ]);

        $employee = Pegawai::findOrFail($request->employee_id);

        $validated['from_division_id'] = $employee->divisi_id;
        $validated['from_position_id'] = $employee->jabatan_id;
        $validated['from_office_id'] = $employee->kantor_id;

        DB::transaction(function () use ($validated, $employee) {
            MutasiPegawai::create($validated);

            $employee->update([
                'divisi_id' => $validated['to_division_id'] ?? $employee->divisi_id,
                'jabatan_id' => $validated['to_position_id'] ?? $employee->jabatan_id,
                'kantor_id' => $validated['to_office_id'] ?? $employee->kantor_id,
            ]);
        });

        $type = $validated['type'];
        $message = '';
        $title = 'Informasi Mutasi Kepegawaian';
        $icon = 'briefcase';
        $notifType = 'info';

        if (in_array($type, ['promosi', 'rotasi', 'mutasi'])) {
            $message = "Selamat! Anda telah menjalani proses {$type}. Silakan cek detail perubahan pada profil Anda. Sukses selalu!";
            $notifType = 'success';
            $icon = 'award';
        } elseif ($type === 'demosi') {
            $message = "Tetap semangat dan terus berkembang. Anda telah menjalani proses {$type}. Silakan cek detail perubahan pada profil Anda.";
            $notifType = 'warning';
            $icon = 'trending-down';
        }

        try {
            $employee->notify(new \App\Notifications\SystemNotification([
                'title' => $title,
                'message' => $message,
                'url' => route('employee.mutations.index'),
                'type' => $notifType,
                'icon' => $icon,
            ]));
        } catch (\Exception $e) {
            //
        }

        return redirect()->route('mutations.index')->with('success', 'Mutasi berhasil diproses dan notifikasi telah dikirim.');
    }

    public function show(MutasiPegawai $mutation)
    {
        return view('mutations.show', compact('mutation'));
    }

    public function print(MutasiPegawai $mutation)
    {
        $mutation->load(['employee', 'fromOffice', 'toOffice', 'toDivision', 'toPosition']);

        $months = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $monthRoman = $months[date('n')];

        if ($mutation->mutation_code) {
            $parts = explode('/', $mutation->mutation_code);
            $sequence = $parts[0];
        } else {
            $sequence = str_pad($mutation->id, 3, '0', STR_PAD_LEFT);
        }

        $officeCode = 'KP';
        $signerCode = 'DZ';

        $admin = auth()->user();

        $officeName = '';
        if ($admin->role) {
            $roleName = $admin->role->role_name;
        }

        $officeName = '';
        if ($admin->office) {
            $officeName = $admin->office->office_name;
        } elseif ($admin->employee && $admin->employee->kantor) {
            $officeName = $admin->employee->kantor->office_name;
        }

        if (in_array($roleName, ['Admin', 'Super Admin'])) {
            $officeCode = 'KP';
            $signerCode = 'DZ';
        } elseif ($roleName === 'Admin Daop') {
            $officeNameLower = strtolower($officeName);

            if ($officeNameLower) {
                if (str_contains($officeNameLower, 'daop 1')) {
                    $officeCode = 'D1';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 2')) {
                    $officeCode = 'D2';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 3')) {
                    $officeCode = 'D3';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 4')) {
                    $officeCode = 'D4';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 5')) {
                    $officeCode = 'D5';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 6')) {
                    $officeCode = 'D6';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 7')) {
                    $officeCode = 'D7';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 8')) {
                    $officeCode = 'D8';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'daop 9')) {
                    $officeCode = 'D9';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'divre iii')) {
                    $officeCode = 'DV3';
                    $signerCode = 'DV';
                } elseif (str_contains($officeNameLower, 'divre ii')) {
                    $officeCode = 'DV2';
                    $signerCode = 'DV';
                } elseif (str_contains($officeNameLower, 'divre iv')) {
                    $officeCode = 'DV4';
                    $signerCode = 'DV';
                } elseif (str_contains($officeNameLower, 'divre i')) {
                    $officeCode = 'DV1';
                    $signerCode = 'DV';
                } elseif (str_contains($officeNameLower, 'lrt')) {
                    $officeCode = 'LRT';
                    $signerCode = 'VP';
                } elseif (str_contains($officeNameLower, 'pusat')) {
                    $officeCode = 'KP';
                    $signerCode = 'DZ';
                }
            }
        }

        $vp = null;
        $officeId = $admin->kantor_id ?? ($admin->employee->kantor_id ?? null);

        if (($signerCode == 'VP' || $signerCode == 'DV') && $officeId) {
            $vp = Pegawai::where('kantor_id', $officeId)
                ->whereHas('jabatan', function ($query) {
                    $query->where('name', 'like', '%Vice President%');
                })
                ->first();
        }

        return view('admin.mutations.print', compact('mutation', 'vp', 'monthRoman', 'sequence', 'officeCode', 'signerCode', 'officeName'));
    }
}
