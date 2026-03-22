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

        $payrolls = $query->latest()->paginate(10)->withQueryString();

        return view('admin.payroll.index', compact('payrolls'));
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
                ->first();

            // Count attendances
            $jumlahHadir = Presensi::where('pegawai_id', $employee->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->whereNotNull('jam_masuk')
                ->count();

            $gajiPokok = $employee->jabatan->gaji_pokok;
            $tunjanganJabatan = $employee->jabatan->tunjangan;
            $tunjanganPerumahan = $employee->jabatan->tunjangan_perumahan;
            $tunjanganPajak = $employee->jabatan->tunjangan_pajak;

            // Image Formulas
            $tunjanganAdminBank = 10000;
            $tunjanganJpk = $gajiPokok * 0.04;
            $erJKK = $gajiPokok * 0.0024;
            $erJHT = $gajiPokok * 0.037;
            $erJKM = $gajiPokok * 0.003;
            $tunjanganJpkPensiun = $gajiPokok * 0.02;
            $tunjanganJpBpjs = $gajiPokok * 0.02;

            if ($payroll) {
                if ($payroll->status === 'paid') {
                    $countSkippedPaid++;
                    continue;
                }

                // Update existing record (might have been created by Bulk THR)
                $thr = (($gajiPokok / 30) + ($tunjanganJabatan / 30)) * $payroll->thr_days;
                $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs + $thr + $payroll->bonus;

                $payroll->update([
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
                    'thr' => $thr,
                    'total_gaji' => $totalGaji,
                ]);
                $countUpdated++;
            } else {
                // Create new
                $thrDays = 0;
                $thr = 0;
                $bonus = 0;
                $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs + $thr + $bonus;

                $payroll = Payroll::create([
                    'pegawai_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
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

                // Notify Employee
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

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function edit(Payroll $payroll)
    {
        return view('admin.payroll.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'thr_days' => 'required|integer|min:0',
            'bonus' => 'required|numeric|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
        ]);

        $thr = (($payroll->gaji_pokok / 30) + ($payroll->tunjangan_jabatan / 30)) * $request->thr_days;

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

        $payroll->update([
            'thr_days' => $request->thr_days,
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
            'thr_days' => 'required|integer|min:0',
            'bonus' => 'required|numeric|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        $employees = Pegawai::with('jabatan')->get();
        $count = 0;
        $countCreated = 0;
        $countUpdated = 0;

        foreach ($employees as $employee) {
            if (!$employee->jabatan) continue;

            $payroll = Payroll::where('pegawai_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            $gajiPokok = $employee->jabatan->gaji_pokok;
            $tunjanganJabatan = $employee->jabatan->tunjangan;
            $tunjanganPerumahan = $employee->jabatan->tunjangan_perumahan;
            $tunjanganPajak = $employee->jabatan->tunjangan_pajak;

            // Formulas
            $tunjanganAdminBank = 10000;
            $tunjanganJpk = $gajiPokok * 0.04;
            $erJKK = $gajiPokok * 0.0024;
            $erJHT = $gajiPokok * 0.037;
            $erJKM = $gajiPokok * 0.003;
            $tunjanganJpkPensiun = $gajiPokok * 0.02;
            $tunjanganJpBpjs = $gajiPokok * 0.02;

            $thr = (($gajiPokok / 30) + ($tunjanganJabatan / 30)) * $request->thr_days;

            if ($payroll) {
                if ($payroll->status === 'paid') continue;

                $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs + $thr + $request->bonus;

                $payroll->update([
                    'tunjangan_perumahan' => $tunjanganPerumahan,
                    'tunjangan_admin_bank' => $tunjanganAdminBank,
                    'tunjangan_jpk' => $tunjanganJpk,
                    'tunjangan_pajak' => $tunjanganPajak,
                    'er_jamsostek_jkk' => $erJKK,
                    'er_jamsostek_jht' => $erJHT,
                    'er_jamsostek_jkm' => $erJKM,
                    'tunjangan_jpk_pensiun' => $tunjanganJpkPensiun,
                    'tunjangan_jp_bpjs' => $tunjanganJpBpjs,
                    'thr_days' => $request->thr_days,
                    'thr' => $thr,
                    'bonus' => $request->bonus,
                    'keterangan_bonus' => $request->keterangan_bonus,
                    'total_gaji' => $totalGaji,
                ]);
                $countUpdated++;
            } else {
                $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs + $thr + $request->bonus;

                $payroll = Payroll::create([
                    'pegawai_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                    'jumlah_hadir' => 0,
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
                    'thr_days' => $request->thr_days,
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

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Data payroll berhasil dihapus.');
    }
}
