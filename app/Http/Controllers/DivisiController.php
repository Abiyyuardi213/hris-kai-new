<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Divisi::query();

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

        $divisions = $query->paginate(10)->withQueryString();
        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        $nextNumber = Divisi::count() + 1;
        return view('divisions.create', compact('nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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

        return view('divisions.edit', compact('division', 'number'));
    }

    public function update(Request $request, Divisi $division)
    {
        $validated = $request->validate([
            'code' => 'required|unique:divisions,code,' . $division->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $division->update($validated);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui');
    }

    public function show(Divisi $division)
    {
        return view('divisions.show', compact('division'));
    }

    public function destroy(Divisi $division)
    {
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus');
    }
}
