<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with(['pegawai.jabatan', 'admin']);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        } else {
            $query->where('month', date('n'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', date('Y'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $periodQuery = Payroll::where('month', $month)->where('year', $year);
        $pendingCount = (clone $periodQuery)->where('status', 'pending')->count();
        $paidCount = (clone $periodQuery)->where('status', 'paid')->count();

        $payrolls = $query->latest()->paginate(10)->withQueryString();

        return view('admin.payroll.index', compact('payrolls', 'pendingCount', 'paidCount'));
    }

    public function generate()
    {
        $employees = Pegawai::with('jabatan')->get();
        return view('admin.payroll.generate', compact('employees'));
    }

    public function processGenerate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        // Siklus penggajian: 26 bulan lalu s/d 25 bulan ini
        $startDate = Carbon::create($year, $month, 26)->subMonth()->startOfDay();
        $endDate = Carbon::create($year, $month, 25)->endOfDay();

        $employees = Pegawai::with('jabatan')->get();

        if ($employees->isEmpty()) {
            return back()->with('error', 'Tidak ditemukan data pegawai untuk di-generate. Pastikan anda memiliki akses ke data pegawai di kantor anda.');
        }

        $countGenerated = 0;
        $countUpdated = 0;
        $countSkippedPaid = 0;
        $countSkippedNoJabatan = 0;

        foreach ($employees as $employee) {
            if (!$employee->jabatan) {
                $countSkippedNoJabatan++;
                continue;
            }

            $payroll = Payroll::where('pegawai_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->where('type', 'payroll')
                ->first();

            $jumlahHadir = Presensi::where('pegawai_id', $employee->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->whereNotNull('jam_masuk')
                ->count();

            $gajiPokok = $employee->jabatan->gaji_pokok;
            $tunjanganJabatan = $employee->jabatan->tunjangan;
            $tunjanganPerumahan = $employee->jabatan->tunjangan_perumahan;
            $tunjanganPajak = $employee->jabatan->tunjangan_pajak;

            $tunjanganAdminBank = 10000;
            $tunjanganJpk = $gajiPokok * 0.04;
            $erJKK = $gajiPokok * 0.0024;
            $erJHT = $gajiPokok * 0.037;
            $erJKM = $gajiPokok * 0.003;
            $tunjanganJpkPensiun = $gajiPokok * 0.02;
            $tunjanganJpBpjs = $gajiPokok * 0.02;

            if ($payroll) {
                // We allow updating even if paid, as some components like salary might be generated after THR
                $wasPaid = $payroll->status === 'paid';

                $thr = 0; // Separate record
                $bonus = 0; // Separate record
                $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs;

                $totalGajiBefore = $payroll->total_gaji;
                $payroll->update([
                    'status' => $totalGaji > $totalGajiBefore ? 'pending' : $payroll->status,
                    'paid_at' => $totalGaji > $totalGajiBefore ? null : $payroll->paid_at,
                    'jumlah_hadir' => $jumlahHadir,
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
                    'thr_days' => 0,
                    'thr' => 0,
                    'bonus' => 0,
                    'total_gaji' => $totalGaji,
                ]);
                $countUpdated++;
            } else {
                $thrDays = 0;
                $thr = 0;
                $bonus = 0;
                $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs;

                $payroll = Payroll::create([
                    'pegawai_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                    'type' => 'payroll',
                    'jumlah_hadir' => $jumlahHadir,
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
                    'thr_days' => $thrDays,
                    'thr' => $thr,
                    'bonus' => $bonus,
                    'total_gaji' => $totalGaji,
                    'status' => 'pending',
                    'generated_by' => Auth::id(),
                ]);

                $employee->notify(new \App\Notifications\PayrollGeneratedNotification($payroll));
                $countGenerated++;
            }
        }

        $message = "Berhasil memproses payroll.";
        $details = [];
        if ($countGenerated > 0) $details[] = "$countGenerated data baru";
        if ($countUpdated > 0) $details[] = "$countUpdated data diperbarui";
        if ($countSkippedPaid > 0) $details[] = "$countSkippedPaid sudah dibayar (lewati)";
        if ($countSkippedNoJabatan > 0) $details[] = "$countSkippedNoJabatan tanpa jabatan (lewati)";

        if (!empty($details)) {
            $message .= " (" . implode(', ', $details) . ")";
        }

        return redirect()->route('admin.payroll.index', ['month' => $month, 'year' => $year])
            ->with('success', $message);
    }

    public function updateStatus(Request $request, Payroll $payroll)
    {
        $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $payroll->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : null,
        ]);

        if ($request->status === 'paid') {
            $payroll->pegawai->notify(new \App\Notifications\PayrollPaidNotification($payroll));
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function edit(Payroll $payroll)
    {
        return view('admin.payroll.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'thr' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
        ]);

        $thr = $request->thr;

        if ($payroll->type === 'thr') {
            $totalGaji = $thr + $request->bonus;
        } else {
            $totalGaji = $payroll->gaji_pokok +
                $payroll->tunjangan_jabatan +
                $payroll->tunjangan_perumahan +
                $payroll->tunjangan_admin_bank +
                $payroll->tunjangan_jpk +
                $payroll->tunjangan_pajak +
                $payroll->er_jamsostek_jkk +
                $payroll->er_jamsostek_jht +
                $payroll->er_jamsostek_jkm +
                $payroll->tunjangan_jpk_pensiun +
                $payroll->tunjangan_jp_bpjs +
                $thr +
                $request->bonus;
        }

        $payroll->update([
            'thr_days' => 0,
            'thr' => $thr,
            'bonus' => $request->bonus,
            'keterangan_bonus' => $request->keterangan_bonus,
            'total_gaji' => $totalGaji,
        ]);

        return redirect()->route('admin.payroll.index', ['month' => $payroll->month, 'year' => $payroll->year])
            ->with('success', 'Data payroll berhasil diperbarui.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'thr' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        // Siklus penggajian: 26 bulan lalu s/d 25 bulan ini
        $startDate = Carbon::create($year, $month, 26)->subMonth()->startOfDay();
        $endDate = Carbon::create($year, $month, 25)->endOfDay();

        $employees = Pegawai::with('jabatan')->get();
        $count = 0;
        $countCreated = 0;
        $countUpdated = 0;

        foreach ($employees as $employee) {
            if (!$employee->jabatan) continue;

            $payroll = Payroll::where('pegawai_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->where('type', 'thr')
                ->first();

            $thr = $request->thr;
            $totalGaji = $thr + $request->bonus;

            if ($payroll) {
                // We allow updating even if paid
                $totalGajiBefore = $payroll->total_gaji;
                $payroll->update([
                    'status' => $totalGaji > $totalGajiBefore ? 'pending' : $payroll->status,
                    'paid_at' => $totalGaji > $totalGajiBefore ? null : $payroll->paid_at,
                    'thr_days' => 0,
                    'thr' => $thr,
                    'bonus' => $request->bonus,
                    'keterangan_bonus' => $request->keterangan_bonus,
                    'total_gaji' => $totalGaji,
                ]);
                $countUpdated++;
            } else {
                $totalGaji = $thr + $request->bonus;

                $payroll = Payroll::create([
                    'pegawai_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                    'type' => 'thr',
                    'jumlah_hadir' => 0,
                    'gaji_pokok' => 0,
                    'tunjangan_jabatan' => 0,
                    'tunjangan_perumahan' => 0,
                    'tunjangan_admin_bank' => 0,
                    'tunjangan_jpk' => 0,
                    'tunjangan_pajak' => 0,
                    'er_jamsostek_jkk' => 0,
                    'er_jamsostek_jht' => 0,
                    'er_jamsostek_jkm' => 0,
                    'tunjangan_jpk_pensiun' => 0,
                    'tunjangan_jp_bpjs' => 0,
                    'thr_days' => 0,
                    'thr' => $thr,
                    'bonus' => $request->bonus,
                    'keterangan_bonus' => $request->keterangan_bonus,
                    'total_gaji' => $totalGaji,
                    'status' => 'pending',
                    'generated_by' => Auth::id(),
                ]);

                $employee->notify(new \App\Notifications\PayrollGeneratedNotification($payroll));
                $countCreated++;
            }
            $count++;
        }

        return redirect()->route('admin.payroll.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', "Berhasil memproses $count data THR/Bonus secara massal ($countCreated baru, $countUpdated diperbarui).");
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        $payrolls = Payroll::where('month', $request->month)
            ->where('year', $request->year)
            ->where('status', 'pending')
            ->get();

        foreach ($payrolls as $payroll) {
            $payroll->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            
            // Notify employee
            $payroll->pegawai->notify(new \App\Notifications\PayrollPaidNotification($payroll));
        }

        return back()->with('success', 'Semua payroll pada periode ini telah berhasil disetujui dan notifikasi telah dikirim ke pegawai.');
    }
    
    public function bulkReject(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        // If there are PENDING ones, "Reject All" means deleting/rejecting the draft
        $pendingQuery = Payroll::where('month', $request->month)
            ->where('year', $request->year)
            ->where('status', 'pending');
            
        if ($pendingQuery->count() > 0) {
            $count = $pendingQuery->count();
            $pendingQuery->delete();
            return back()->with('success', "Berhasil menolak dan menghapus $count draft payroll pada periode ini.");
        }

        // Only if NO pending ones, we revert the PAID ones
        $paidQuery = Payroll::where('month', $request->month)
            ->where('year', $request->year)
            ->where('status', 'paid');
            
        $count = $paidQuery->count();
        if ($count > 0) {
            $paidQuery->update([
                'status' => 'pending',
                'paid_at' => null,
            ]);
            return back()->with('success', "Status $count data payroll pada periode ini telah dikembalikan ke Pending.");
        }

        return back()->with('error', 'Tidak ditemukan data payroll untuk ditolak pada periode ini.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Data payroll berhasil dihapus.');
    }
}
