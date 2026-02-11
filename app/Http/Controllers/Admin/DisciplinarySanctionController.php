<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisciplinarySanction;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DisciplinarySanctionController extends Controller
{
    public function index(Request $request)
    {
        $query = DisciplinarySanction::with('employee');

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

        $sanctions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.disciplinary_sanctions.index', compact('sanctions'));
    }

    public function create()
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        return view('admin.disciplinary_sanctions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:pegawais,id',
            'type' => 'required|in:Verbal,SP1,SP2,SP3,Termination',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $status = 'Active';
        if ($validated['end_date'] && now()->gt($validated['end_date'])) {
            $status = 'Expired';
        }

        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('disciplinary_docs', 'public');
        }

        DisciplinarySanction::create([
            'employee_id' => $validated['employee_id'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'document_path' => $path,
            'status' => $status
        ]);

        return redirect()->route('admin.sanctions.index')->with('success', 'Sanksi disiplin berhasil ditambahkan.');
    }

    public function show(DisciplinarySanction $sanction)
    {
        return view('admin.disciplinary_sanctions.show', compact('sanction'));
    }

    public function destroy(DisciplinarySanction $sanction)
    {
        if ($sanction->document_path) {
            Storage::disk('public')->delete($sanction->document_path);
        }
        $sanction->delete();
        return redirect()->route('admin.sanctions.index')->with('success', 'Sanksi disiplin berhasil dihapus.');
    }

    public function print(DisciplinarySanction $sanction)
    {
        // Get the Vice President of the same office (DAOP)
        $employee = $sanction->employee;
        $vp = null;

        if ($employee->kantor_id) {
            $vp = Pegawai::where('kantor_id', $employee->kantor_id)
                ->whereHas('jabatan', function ($query) {
                    $query->where('name', 'like', '%Vice President%');
                })
                ->first();
        }

        return view('admin.disciplinary_sanctions.print', compact('sanction', 'vp'));
    }
}
