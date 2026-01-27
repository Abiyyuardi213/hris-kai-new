<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class KotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kota::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('province')) {
            $query->where('province_name', $request->province);
        }

        $cities = $query->orderBy('province_name')->orderBy('name')->paginate(10)->withQueryString();
        $provinces = Kota::select('province_name')->distinct()->orderBy('province_name')->get();

        return view('cities.index', compact('cities', 'provinces'));
    }

    public function create()
    {
        return view('cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:cities,code',
            'name' => 'required',
            'province_code' => 'required',
            'province_name' => 'required',
        ]);

        Kota::create($validated);

        return redirect()->route('cities.index')->with('success', 'Kota berhasil ditambahkan');
    }

    public function edit(Kota $city)
    {
        return view('cities.edit', compact('city'));
    }

    public function update(Request $request, Kota $city)
    {
        $validated = $request->validate([
            'code' => 'required|unique:cities,code,' . $city->id,
            'name' => 'required',
            'province_code' => 'required',
            'province_name' => 'required',
        ]);

        $city->update($validated);

        return redirect()->route('cities.index')->with('success', 'Kota berhasil diperbarui');
    }

    public function destroy(Kota $city)
    {
        $city->delete();
        return redirect()->route('cities.index')->with('success', 'Kota berhasil dihapus');
    }

    public function sync()
    {
        try {
            // Fetch Provinces
            $provincesResponse = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

            if ($provincesResponse->failed()) {
                return redirect()->route('cities.index')->with('error', 'Gagal mengambil data provinsi');
            }

            $provinces = $provincesResponse->json();
            $count = 0;

            foreach ($provinces as $province) {
                $provinceId = $province['id'];
                $provinceName = $province['name'];

                // Fetch Regencies for this province
                $regenciesResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

                if ($regenciesResponse->successful()) {
                    $regencies = $regenciesResponse->json();

                    foreach ($regencies as $regency) {
                        Kota::updateOrCreate(
                            ['code' => $regency['id']],
                            [
                                'name' => $regency['name'],
                                'province_code' => $provinceId,
                                'province_name' => $provinceName,
                            ]
                        );
                        $count++;
                    }
                }
            }

            return redirect()->route('cities.index')->with('success', "Sinkronisasi berhasil. {$count} data kota diperbarui.");
        } catch (\Exception $e) {
            return redirect()->route('cities.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
