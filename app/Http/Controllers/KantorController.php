<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use App\Models\Kota;
use Illuminate\Http\Request;

class KantorController extends Controller
{
    public function index(Request $request)
    {
        $query = Kantor::with('city');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('office_name', 'like', "%{$search}%")
                    ->orWhere('office_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('province')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('province_name', $request->province);
            });
        }

        $offices = $query->latest()->paginate(10)->withQueryString();
        $cities = Kota::select('id', 'name')->orderBy('name')->get();
        $provinces = Kota::select('province_name')->distinct()->orderBy('province_name')->get();

        return view('offices.index', compact('offices', 'cities', 'provinces'));
    }

    public function create()
    {
        $cities = Kota::select('id', 'name', 'province_name')->orderBy('name')->get();
        return view('offices.create', compact('cities'));
    }

    public function getNextCode(Request $request)
    {
        $cityId = $request->city_id;
        $city = Kota::find($cityId);

        if (!$city) {
            return response()->json(['code' => '']);
        }

        // Familiar city abbreviations (KAI/IATA style)
        $cityMappings = [
            'SURABAYA' => 'SUB',
            'BANDUNG' => 'BDG',
            'SEMARANG' => 'SMG',
            'JAKARTA' => 'JKT',
            'YOGYAKARTA' => 'YOG',
            'SURAKARTA' => 'SLO',
            'SOLO' => 'SLO',
            'MALANG' => 'MLG',
            'MEDAN' => 'MDN',
            'MAKASSAR' => 'MKS',
            'PALEMBANG' => 'PLM',
            'DENPASAR' => 'DPS',
            'BALIKPAPAN' => 'BPN',
            'SAMARINDA' => 'SRI',
            'PONTIANAK' => 'PNK',
            'BANJARMASIN' => 'BJM',
            'MANADO' => 'MND',
            'CIREBON' => 'CN',
            'TEGAL' => 'TG',
            'PURWOKERTO' => 'PWT',
            'MADIUN' => 'MN',
            'JEMBER' => 'JR',
            'BANYUWANGI' => 'BW',
            'CILACAP' => 'CP',
            'BANDAR LAMPUNG' => 'TNK',
            'BANDA ACEH' => 'BTJ',
            'MATARAM' => 'MTR',
            'KUPANG' => 'KOE',
            'JAYAPURA' => 'DJJ',
            'AMBON' => 'AMQ',
            'PALU' => 'PLW',
            'KENDARI' => 'KDI',
            'GORONTALO' => 'GTO',
            'MAMUJU' => 'MJU',
        ];

        $cityName = strtoupper($city->name);

        // Remove text in parentheses (e.g., "(LAMPUNG)")
        $cityName = preg_replace('/\s*\(.*\)/', '', $cityName);

        // Clean city name from "KOTA " or "KABUPATEN "
        $cleanName = str_replace(['KOTA ', 'KABUPATEN '], '', $cityName);
        $cleanName = trim($cleanName);

        // Check if we have a mapping
        if (isset($cityMappings[$cleanName])) {
            $prefix = $cityMappings[$cleanName];
        } else {
            // Fallback: use first 3 letters of the cleaned name
            $prefix = substr($cleanName, 0, 3);
        }

        $prefix = strtoupper($prefix);

        // Count existing offices in this city
        $count = Kantor::where('city_id', $cityId)->count();
        $nextNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $code = $prefix . '-' . $nextNumber;

        return response()->json(['code' => $code]);
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

        Kantor::create($validated);

        return redirect()->route('offices.index')->with('success', 'Kantor berhasil ditambahkan');
    }

    public function edit(Kantor $office)
    {
        $cities = Kota::select('id', 'name', 'province_name')->orderBy('name')->get();
        return view('offices.edit', compact('office', 'cities'));
    }

    public function update(Request $request, Kantor $office)
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

    public function destroy(Kantor $office)
    {
        $office->delete();
        return redirect()->route('offices.index')->with('success', 'Kantor berhasil dihapus');
    }
}
