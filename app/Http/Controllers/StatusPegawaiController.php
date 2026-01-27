<?php

namespace App\Http\Controllers;

use App\Models\StatusPegawai;
use Illuminate\Http\Request;

class StatusPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = StatusPegawai::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sorting logic
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $statuses = $query->paginate(10)->withQueryString();
        return view('employment_statuses.index', compact('statuses'));
    }

    public function create()
    {
        $nextNumber = StatusPegawai::count() + 1;
        return view('employment_statuses.create', compact('nextNumber'));
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

    public function edit(StatusPegawai $employmentStatus)
    {
        return view('employment_statuses.edit', compact('employmentStatus'));
    }

    public function update(Request $request, StatusPegawai $employmentStatus)
    {
        $validated = $request->validate([
            'code' => 'required|unique:employment_statuses,code,' . $employmentStatus->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $employmentStatus->update($validated);

        return redirect()->route('employment-statuses.index')->with('success', 'Status pegawai berhasil diperbarui');
    }

    public function destroy(StatusPegawai $employmentStatus)
    {
        $employmentStatus->delete();
        return redirect()->route('employment-statuses.index')->with('success', 'Status pegawai berhasil dihapus');
    }
}
