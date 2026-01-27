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

        // Search by Employee Name or NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter by Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Division (Target)
        if ($request->filled('division')) {
            $query->where('to_division_id', $request->division);
        }

        // Filter by Office (Target)
        if ($request->filled('office')) {
            $query->where('to_office_id', $request->office);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('mutation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('mutation_date', '<=', $request->end_date);
        }

        $mutations = $query->latest()->paginate(10);

        // Metadata for filters
        $divisions = Divisi::orderBy('name')->get();
        $offices = Kantor::orderBy('office_name')->get();
        $types = ['promosi', 'demosi', 'rotasi', 'mutasi'];

        return view('mutations.index', compact('mutations', 'divisions', 'offices', 'types'));
    }

    public function create()
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        $divisions = Divisi::all();
        $positions = Jabatan::all();
        $offices = Kantor::all();

        return view('mutations.create', compact('employees', 'divisions', 'positions', 'offices'));
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
            'file_sk' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $employee = Pegawai::findOrFail($request->employee_id);

        $validated['from_division_id'] = $employee->divisi_id;
        $validated['from_position_id'] = $employee->jabatan_id;
        $validated['from_office_id'] = $employee->kantor_id;

        if ($request->hasFile('file_sk')) {
            $path = $request->file('file_sk')->store('mutations', 'public');
            $validated['file_sk'] = $path;
        }

        DB::transaction(function () use ($validated, $employee) {
            // Create Mutation Record
            MutasiPegawai::create($validated);

            // Update Employee Data
            $employee->update([
                'divisi_id' => $validated['to_division_id'] ?? $employee->divisi_id,
                'jabatan_id' => $validated['to_position_id'] ?? $employee->jabatan_id,
                'kantor_id' => $validated['to_office_id'] ?? $employee->kantor_id,
            ]);
        });

        return redirect()->route('mutations.index')->with('success', 'Mutasi berhasil diproses');
    }

    public function show(MutasiPegawai $mutation)
    {
        return view('mutations.show', compact('mutation'));
    }
}
