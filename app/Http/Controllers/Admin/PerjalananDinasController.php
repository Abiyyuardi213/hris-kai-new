<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasPeserta;
use App\Models\Pegawai;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerjalananDinasController extends Controller
{
    public function index(Request $request)
    {
        $query = PerjalananDinas::with(['pemohon', 'pegawaiPeserta'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_surat_tugas', 'like', '%' . $request->search . '%')
                    ->orWhere('tujuan', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pemohon', function ($sq) use ($request) {
                        $sq->where('nama_lengkap', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trips = $query->paginate(15)->withQueryString();

        return view('admin.perjalanan_dinas.index', compact('trips'));
    }

    public function create()
    {
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        return view('admin.perjalanan_dinas.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id', // Pemohon utama/penanggung jawab
            'peserta_ids' => 'required|array|min:1',
            'peserta_ids.*' => 'exists:pegawais,id',
            'no_surat_tugas' => 'nullable|string|unique:perjalanan_dinas,no_surat_tugas',
            'tujuan' => 'required|string',
            'keperluan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_transportasi' => 'nullable|string',
            'estimasi_biaya' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $noSurat = $validated['no_surat_tugas'] ?? PerjalananDinas::generateNoSuratTugas();

            $trip = PerjalananDinas::create([
                'pegawai_id' => $validated['pegawai_id'],
                'no_surat_tugas' => $noSurat,
                'tujuan' => $validated['tujuan'],
                'keperluan' => $validated['keperluan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'jenis_transportasi' => $validated['jenis_transportasi'],
                'estimasi_biaya' => $validated['estimasi_biaya'],
                'status' => 'Disetujui', // Admin creating usually means auto-approved
                'disetujui_oleh' => Auth::id(),
            ]);

            foreach ($validated['peserta_ids'] as $pesertaId) {
                PerjalananDinasPeserta::create([
                    'perjalanan_dinas_id' => $trip->id,
                    'pegawai_id' => $pesertaId,
                ]);

                // Notify each participant
                $pegawai = Pegawai::find($pesertaId);
                if ($pegawai) {
                    $pegawai->notify(new SystemNotification([
                        'title' => 'Penugasan Perjalanan Dinas',
                        'message' => "Anda telah ditugaskan perjalanan dinas ke {$trip->tujuan} pada " . $trip->tanggal_mulai->format('d M Y'),
                        'url' => route('employee.perjalanan_dinas.index'),
                        'type' => 'info',
                        'icon' => 'briefcase',
                    ]));
                }
            }

            DB::commit();
            return redirect()->route('admin.perjalanan_dinas.index')->with('success', 'Perjalanan dinas berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $trip = PerjalananDinas::with(['pemohon', 'pegawaiPeserta', 'pengetuju'])->findOrFail($id);
        return view('admin.perjalanan_dinas.show', compact('trip'));
    }

    public function updateStatus(Request $request, $id)
    {
        $trip = PerjalananDinas::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pengajuan,Disetujui,Ditolak,Sedang Berjalan,Selesai,Dibatalkan',
            'catatan_persetujuan' => 'nullable|string',
            'no_surat_tugas' => 'nullable|string|unique:perjalanan_dinas,no_surat_tugas,' . $id,
            'realisasi_biaya' => 'nullable|numeric|min:0',
        ]);

        $noSurat = $request->no_surat_tugas ?: $trip->no_surat_tugas;
        if ($request->status === 'Disetujui' && !$noSurat) {
            $noSurat = PerjalananDinas::generateNoSuratTugas();
        }

        $trip->update([
            'status' => $request->status,
            'catatan_persetujuan' => $request->catatan_persetujuan,
            'no_surat_tugas' => $noSurat,
            'realisasi_biaya' => $request->realisasi_biaya,
            'disetujui_oleh' => in_array($request->status, ['Disetujui', 'Ditolak']) ? Auth::id() : $trip->disetujui_oleh,
        ]);

        // Notify pemohon
        if ($trip->pemohon) {
            $trip->pemohon->notify(new SystemNotification([
                'title' => 'Update Status Perjalanan Dinas',
                'message' => "Status perjalanan dinas ke {$trip->tujuan} telah diupdate menjadi: {$trip->status}",
                'url' => route('employee.perjalanan_dinas.index'),
                'type' => $request->status == 'Disetujui' ? 'success' : ($request->status == 'Ditolak' ? 'danger' : 'info'),
                'icon' => 'briefcase',
            ]));
        }

        return back()->with('success', 'Status perjalanan dinas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $trip = PerjalananDinas::findOrFail($id);
        $trip->delete();
        return redirect()->route('admin.perjalanan_dinas.index')->with('success', 'Data perjalanan dinas berhasil dihapus.');
    }
}
