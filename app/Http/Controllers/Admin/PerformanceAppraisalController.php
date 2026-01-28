<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerformanceAppraisal;
use App\Models\PerformanceAppraisalItem;
use App\Models\KpiIndicator;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceAppraisalController extends Controller
{
    public function index(Request $request)
    {
        $query = PerformanceAppraisal::with(['pegawai', 'appraiser'])
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $appraisals = $query->paginate(15)->withQueryString();

        return view('admin.performance.index', compact('appraisals'));
    }

    public function create()
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        return view('admin.performance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        $appraisal = PerformanceAppraisal::create([
            'pegawai_id' => $validated['pegawai_id'],
            'appraiser_id' => Auth::id(),
            'periode_mulai' => $validated['periode_mulai'],
            'periode_selesai' => $validated['periode_selesai'],
            'tahun' => $validated['tahun'],
            'status' => 'Draft',
        ]);

        return redirect()->route('admin.performance.edit', $appraisal->id)
            ->with('success', 'Penilaian berhasil dibuat, silakan masukkan skor indikator.');
    }

    public function edit($id)
    {
        $appraisal = PerformanceAppraisal::with(['pegawai', 'items.indicator'])->findOrFail($id);
        $indicators = KpiIndicator::all();

        // If no items yet, prepare them
        if ($appraisal->items->isEmpty()) {
            foreach ($indicators as $indicator) {
                PerformanceAppraisalItem::create([
                    'performance_appraisal_id' => $appraisal->id,
                    'kpi_indicator_id' => $indicator->id,
                    'score' => 0,
                ]);
            }
            $appraisal->load('items.indicator');
        }

        return view('admin.performance.edit', compact('appraisal', 'indicators'));
    }

    public function update(Request $request, $id)
    {
        $appraisal = PerformanceAppraisal::findOrFail($id);

        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0|max:100',
            'comments' => 'nullable|array',
            'catatan_reviewer' => 'nullable|string',
            'status' => 'required|in:Draft,Selesai',
        ]);

        try {
            DB::beginTransaction();

            $totalWeightedScore = 0;
            $totalWeightUsed = 0;

            foreach ($request->scores as $itemId => $score) {
                $item = PerformanceAppraisalItem::with('indicator')->findOrFail($itemId);
                $item->update([
                    'score' => $score,
                    'comment' => $request->comments[$itemId] ?? null,
                ]);

                $totalWeightedScore += ($score * ($item->indicator->weight / 100));
                $totalWeightUsed += $item->indicator->weight;
            }

            // Normalize score if weights dont add exactly to 100 (optional logic)
            $finalScore = $totalWeightedScore;

            $appraisal->update([
                'total_score' => $finalScore,
                'rating' => $appraisal->calculateRating($finalScore),
                'catatan_reviewer' => $request->catatan_reviewer,
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->route('admin.performance.show', $appraisal->id)->with('success', 'Penilaian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $appraisal = PerformanceAppraisal::with(['pegawai.jabatan', 'pegawai.divisi', 'appraiser', 'items.indicator'])->findOrFail($id);
        return view('admin.performance.show', compact('appraisal'));
    }

    public function print($id)
    {
        $appraisal = PerformanceAppraisal::with(['pegawai.jabatan', 'pegawai.divisi', 'appraiser', 'items.indicator'])->findOrFail($id);
        return view('admin.performance.print', compact('appraisal'));
    }

    public function destroy($id)
    {
        PerformanceAppraisal::findOrFail($id)->delete();
        return redirect()->route('admin.performance.index')->with('success', 'Data penilaian berhasil dihapus.');
    }
}
