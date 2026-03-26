<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Divisi;
use App\Models\Payroll;
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
                $query->orderBy('name', 'asc')->orderBy('id', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc')->orderBy('id', 'desc');
                break;
            case 'latest':
            default:
                $query->latest()->orderBy('id', 'desc');
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
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            'tunjangan_perumahan' => 'required|numeric|min:0',
            'tunjangan_pajak' => 'required|numeric|min:0',
        ]);

        $position = Jabatan::create($validated);

        // Calculate redirect page based on sort
        $sort = $request->get('sort', 'latest');
        $perPage = 10;
        $page = 1;

        if ($sort === 'latest') {
            $page = 1;
        } elseif ($sort === 'name_asc') {
            $count = Jabatan::where('name', '<', $position->name)->count();
            $page = floor($count / $perPage) + 1;
        } elseif ($sort === 'name_desc') {
            $count = Jabatan::where('name', '>', $position->name)->count();
            $page = floor($count / $perPage) + 1;
        }

        return redirect()->route('positions.index', [
            'page' => $page,
            'sort' => $sort,
        ])->with('success', 'Jabatan berhasil ditambahkan');
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
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            'tunjangan_perumahan' => 'required|numeric|min:0',
            'tunjangan_pajak' => 'required|numeric|min:0',
        ]);

        $position->update($validated);

        $payrolls = Payroll::whereIn('pegawai_id', $position->employees()->pluck('id'))
            ->where('status', 'pending')
            ->get();

        foreach ($payrolls as $payroll) {
            $gajiPokok = $position->gaji_pokok;
            $tunjanganJabatan = $position->tunjangan;
            $tunjanganPerumahan = $position->tunjangan_perumahan;
            $tunjanganPajak = $position->tunjangan_pajak;

            // Formulas
            $tunjanganAdminBank = 10000;
            $tunjanganJpk = $gajiPokok * 0.04;
            $erJKK = $gajiPokok * 0.0024;
            $erJHT = $gajiPokok * 0.037;
            $erJKM = $gajiPokok * 0.003;
            $tunjanganJpkPensiun = $gajiPokok * 0.02;
            $tunjanganJpBpjs = $gajiPokok * 0.02;

            $thr = (($gajiPokok / 30) + ($tunjanganJabatan / 30)) * $payroll->thr_days;

            $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs + $thr + $payroll->bonus;

            $payroll->update([
                'gaji_pokok' => $gajiPokok,
                'tunjangan_jabatan' => $tunjanganJabatan,
                'tunjangan_perumahan' => $tunjanganPerumahan,
                'tunjangan_admin_bank' => $tunjanganAdminBank,
                'tunjangan_jpk' => $tunjanganJpk,
                'tunjangan_pajak' => $tunjanganPajak,
                'er_jamsostek_jkk' => $erJKK,
                'er_jamsostek_jht' => $erJHT,
                'er_jamsostek_jkm' => $erJKM,
                'tunjangan_jpk_pensiun' => $tunjanganJpkPensiun,
                'tunjangan_jp_bpjs' => $tunjanganJpBpjs,
                'thr' => $thr,
                'total_gaji' => $totalGaji,
            ]);
        }

        return redirect()->to(route('positions.index', [
            'page' => $request->page,
            'sort' => $request->sort,
            'search' => $request->search
        ]) . "#position-{$position->id}")->with('success', 'Jabatan berhasil diperbarui dan payroll pending telah disesuaikan');

    }

    public function destroy(Jabatan $position)
    {
        $position->delete();

        // Check if the current page is empty after deletion
        $page = request()->get('page', 1);
        $sort = request()->get('sort', 'latest');
        $search = request()->get('search');

        // If we are deep in pages, check if we need to go back one page
        if ($page > 1) {
            $query = Jabatan::query();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            }
            $remaining = $query->count();
            $totalPages = ceil($remaining / 10); // 10 is perPage

            if ($page > $totalPages && $totalPages > 0) {
                $page = $totalPages;
            }
        }

        return redirect()->route('positions.index', [
            'page' => $page,
            'sort' => $sort,
            'search' => $search
        ])->with('success', 'Jabatan berhasil dihapus');
    }
}
