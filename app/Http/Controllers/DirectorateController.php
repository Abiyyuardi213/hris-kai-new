<?php

namespace App\Http\Controllers;

use App\Models\Directorate;
use Illuminate\Http\Request;

class DirectorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Directorate::query();

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

        $directorates = $query->paginate(10)->withQueryString();
        return view('directorates.index', compact('directorates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextNumber = Directorate::count() + 1;
        return view('directorates.create', compact('nextNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:directorates,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Directorate::create($validated);

        return redirect()->route('directorates.index')->with('success', 'Direktorat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Directorate $directorate)
    {
        return view('directorates.show', compact('directorate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Directorate $directorate)
    {
        // specific logic to extract number from code (e.g. D01 -> 01)
        // Assume code might end in digits
        preg_match('/(\d+)$/', $directorate->code, $matches);
        $number = isset($matches[1]) ? $matches[1] : str_pad($directorate->id, 2, '0', STR_PAD_LEFT);

        return view('directorates.edit', compact('directorate', 'number'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Directorate $directorate)
    {
        $validated = $request->validate([
            'code' => 'required|unique:directorates,code,' . $directorate->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $directorate->update($validated);

        return redirect()->route('directorates.index')->with('success', 'Direktorat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Directorate $directorate)
    {
        $directorate->delete();
        return redirect()->route('directorates.index')->with('success', 'Direktorat berhasil dihapus');
    }
}
