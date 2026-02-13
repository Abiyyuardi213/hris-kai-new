<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Divisi;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::query();

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

        $positions = $query->paginate(10)->withQueryString();
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $nextNumber = Jabatan::count() + 1;
        return view('positions.create', compact('nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:positions,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gaji_per_hari' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
        ]);

        Jabatan::create($validated);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit(Jabatan $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Request $request, Jabatan $position)
    {
        $validated = $request->validate([
            'code' => 'required|unique:positions,code,' . $position->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gaji_per_hari' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
        ]);

        $position->update($validated);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Jabatan $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus');
    }
}
