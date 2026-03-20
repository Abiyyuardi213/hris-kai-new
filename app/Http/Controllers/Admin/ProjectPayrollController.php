<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectPayroll;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectPayroll::with(['pegawai.jabatan', 'admin']);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        } else {
            $query->where('month', date('n'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', date('Y'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $projectPayrolls = $query->latest()->paginate(10)->withQueryString();

        return view('admin.project-payroll.index', compact('projectPayrolls'));
    }

    public function create()
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        return view('admin.project-payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'project_name' => 'required|string|max:255',
            'total_pay' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        ProjectPayroll::create([
            'pegawai_id' => $request->pegawai_id,
            'project_name' => $request->project_name,
            'total_pay' => $request->total_pay,
            'keterangan' => $request->keterangan,
            'month' => $request->month,
            'year' => $request->year,
            'status' => 'pending',
            'generated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.project-payroll.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', 'Payroll project berhasil ditambahkan.');
    }

    public function edit(ProjectPayroll $projectPayroll)
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        return view('admin.project-payroll.edit', compact('projectPayroll', 'employees'));
    }

    public function update(Request $request, ProjectPayroll $projectPayroll)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'project_name' => 'required|string|max:255',
            'total_pay' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        if ($projectPayroll->status === 'paid') {
            return back()->with('error', 'Tidak dapat mengubah data yang sudah dibayar.');
        }

        $projectPayroll->update([
            'pegawai_id' => $request->pegawai_id,
            'project_name' => $request->project_name,
            'total_pay' => $request->total_pay,
            'keterangan' => $request->keterangan,
            'month' => $request->month,
            'year' => $request->year,
        ]);

        return redirect()->route('admin.project-payroll.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', 'Data payroll project berhasil diperbarui.');
    }

    public function updateStatus(Request $request, ProjectPayroll $projectPayroll)
    {
        $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $projectPayroll->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : null,
        ]);

        return back()->with('success', 'Status pembayaran payroll project berhasil diperbarui.');
    }

    public function destroy(ProjectPayroll $projectPayroll)
    {
        if ($projectPayroll->status === 'paid') {
            return back()->with('error', 'Tidak dapat menghapus data yang sudah dibayar.');
        }

        $projectPayroll->delete();
        return back()->with('success', 'Data payroll project berhasil dihapus.');
    }
}
