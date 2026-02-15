<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

use App\Models\Directorate;

class DivisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Divisi::with('directorate');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('directorate', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('directorate_id')) {
            $query->where('directorate_id', $request->directorate_id);
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

        $divisions = $query->paginate(10)->withQueryString();
        $directorates = Directorate::orderBy('name')->get();

        return view('divisions.index', compact('divisions', 'directorates'));
    }

    public function create()
    {
        $nextNumber = Divisi::count() + 1;
        $directorates = Directorate::orderBy('name')->get();
        return view('divisions.create', compact('nextNumber', 'directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'directorate_id' => 'required|exists:directorates,id',
            'code' => 'required|unique:divisions,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Divisi::create($validated);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil ditambahkan');
    }

    public function edit(Divisi $division)
    {
        // specific logic to extract number from code (e.g. IT-DEV-001 -> 001)
        $parts = explode('-', $division->code);
        $number = end($parts);

        // Ensure it's numeric, otherwise fallback to id padded
        if (!is_numeric($number)) {
            $number = str_pad($division->id, 3, '0', STR_PAD_LEFT);
        }

        $directorates = Directorate::orderBy('name')->get();

        return view('divisions.edit', compact('division', 'number', 'directorates'));
    }

    public function update(Request $request, Divisi $division)
    {
        $validated = $request->validate([
            'directorate_id' => 'required|exists:directorates,id',
            'code' => 'required|unique:divisions,code,' . $division->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $division->update($validated);

        return redirect()->route('divisions.index', $request->query())->with('success', 'Divisi berhasil diperbarui');
    }

    public function show(Divisi $division)
    {
        $division->load('directorate');
        return view('divisions.show', compact('division'));
    }

    public function destroy(Request $request, Divisi $division)
    {
        try {
            $division->delete();
            return redirect()->route('divisions.index', $request->query())->with('success', 'Divisi berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('divisions.dependencies', $division->id)
                    ->with('error', 'Divisi tidak dapat dihapus karena masih memiliki data terkait. Silakan tinjau data di bawah ini.');
            }
            throw $e;
        }
    }

    public function dependencies(Divisi $division)
    {
        $division->load(['employees', 'mutationsFrom', 'mutationsTo']);
        return view('divisions.dependencies', compact('division'));
    }
}
