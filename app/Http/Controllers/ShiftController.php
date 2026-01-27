<?php

namespace App\Http\Controllers;

use App\Models\ShiftKerja;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = ShiftKerja::orderBy('start_time')->get();
        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ShiftKerja::create($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditambahkan');
    }

    public function edit(ShiftKerja $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, ShiftKerja $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $shift->update($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil diperbarui');
    }

    public function destroy(ShiftKerja $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift berhasil dihapus');
    }
}
