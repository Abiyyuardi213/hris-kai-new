<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HariLiburController extends Controller
{
    public function syncFromApi()
    {
        try {
            $response = Http::get('https://libur.deno.dev/api');

            if ($response->successful()) {
                $holidays = $response->json();

                foreach ($holidays as $item) {
                    HariLibur::updateOrCreate(
                        ['date' => $item['date']],
                        [
                            'name' => $item['name'],
                            'description' => 'Disinkronkan dari API',
                        ]
                    );
                }

                return redirect()->route('holidays.index')->with('success', 'Berhasil menyinkronkan data hari libur dari API.');
            }

            return redirect()->route('holidays.index')->with('error', 'Gagal mengambil data dari API.');
        } catch (\Exception $e) {
            return redirect()->route('holidays.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function index(Request $request)
    {
        $query = HariLibur::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $holidays = $query->orderBy('date', 'desc')->paginate(10);
        return view('holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        HariLibur::create($validated);

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil ditambahkan');
    }

    public function destroy(HariLibur $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil dihapus');
    }
}
