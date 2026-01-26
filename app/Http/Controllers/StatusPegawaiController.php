<?php

namespace App\Http\Controllers;

use App\Models\StatusPegawai;
use Illuminate\Http\Request;

class StatusPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = StatusPegawai::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        $statuses = $query->paginate(10);
        return view('employment_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('employment_statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:employment_statuses,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        StatusPegawai::create($validated);

        return redirect()->route('employment-statuses.index')->with('success', 'Status pegawai berhasil ditambahkan');
    }

    public function edit(EmploymentStatus $employmentStatus)
    {
        return view('employment_statuses.edit', compact('employmentStatus'));
    }

    public function update(Request $request, EmploymentStatus $employmentStatus)
    {
        $validated = $request->validate([
            'code' => 'required|unique:employment_statuses,code,' . $employmentStatus->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $employmentStatus->update($validated);

        return redirect()->route('employment-statuses.index')->with('success', 'Status pegawai berhasil diperbarui');
    }

    public function destroy(EmploymentStatus $employmentStatus)
    {
        $employmentStatus->delete();
        return redirect()->route('employment-statuses.index')->with('success', 'Status pegawai berhasil dihapus');
    }
}
