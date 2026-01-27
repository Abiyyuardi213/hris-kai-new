<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['office', 'division']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->latest()->paginate(10);
        $offices = \App\Models\Kantor::orderBy('office_name')->get();
        $divisions = \App\Models\Divisi::orderBy('name')->get();

        return view('assets.index', compact('assets', 'offices', 'divisions'));
    }

    public function create()
    {
        $offices = \App\Models\Kantor::orderBy('office_name')->get();
        $divisions = \App\Models\Divisi::orderBy('name')->get();
        return view('assets.create', compact('offices', 'divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:assets,code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|in:good,repair,broken,lost',
            'office_id' => 'nullable|exists:offices,id',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'nullable|string',
        ]);

        Aset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan');
    }

    public function edit(Aset $asset)
    {
        $offices = \App\Models\Kantor::orderBy('office_name')->get();
        $divisions = \App\Models\Divisi::orderBy('name')->get();
        return view('assets.edit', compact('asset', 'offices', 'divisions'));
    }

    public function update(Request $request, Aset $asset)
    {
        $validated = $request->validate([
            'code' => 'required|unique:assets,code,' . $asset->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|in:good,repair,broken,lost',
            'office_id' => 'nullable|exists:offices,id',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui');
    }

    public function destroy(Aset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus');
    }
}
