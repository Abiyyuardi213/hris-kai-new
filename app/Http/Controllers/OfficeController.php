<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\City;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $query = Office::with('city');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('office_name', 'like', "%{$search}%")
                ->orWhere('office_code', 'like', "%{$search}%")
                ->orWhereHas('city', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $offices = $query->paginate(10);
        return view('offices.index', compact('offices'));
    }

    public function create()
    {
        // Limit to prevent huge list, or integrate a search select in frontend later
        $cities = City::select('id', 'name', 'province_name')->orderBy('name')->get();
        return view('offices.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'office_code' => 'required|unique:offices,office_code',
            'office_name' => 'required|string|max:255',
            'office_address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Office::create($validated);

        return redirect()->route('offices.index')->with('success', 'Kantor berhasil ditambahkan');
    }

    public function edit(Office $office)
    {
        $cities = City::select('id', 'name', 'province_name')->orderBy('name')->get();
        return view('offices.edit', compact('office', 'cities'));
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'office_code' => 'required|unique:offices,office_code,' . $office->id,
            'office_name' => 'required|string|max:255',
            'office_address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $office->update($validated);

        return redirect()->route('offices.index')->with('success', 'Data kantor berhasil diperbarui');
    }

    public function destroy(Office $office)
    {
        $office->delete();
        return redirect()->route('offices.index')->with('success', 'Kantor berhasil dihapus');
    }
}
