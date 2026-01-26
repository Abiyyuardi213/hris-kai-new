<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Division;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::with('division');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhereHas('division', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $positions = $query->paginate(10);
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        return view('positions.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:positions,code',
            'name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
            'description' => 'nullable|string',
        ]);

        Position::create($validated);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit(Position $position)
    {
        $divisions = Division::orderBy('name')->get();
        return view('positions.edit', compact('position', 'divisions'));
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'code' => 'required|unique:positions,code,' . $position->id,
            'name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
            'description' => 'nullable|string',
        ]);

        $position->update($validated);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus');
    }
}
