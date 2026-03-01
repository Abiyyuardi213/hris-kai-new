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
        $countGenerated = 0;

        foreach ($employees as $employee) {
            // Check if already generated
            $exists = Payroll::where('pegawai_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) continue;

            if (!$employee->jabatan) continue;

            // Count attendances
            $jumlahHadir = Presensi::where('pegawai_id', $employee->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->whereNotNull('jam_masuk')
                ->count();

            $gajiHarian = $employee->jabatan->gaji_per_hari;
            $tunjanganJabatan = $employee->jabatan->tunjangan;
            $thrDays = 0;
            $thr = 0;
            $bonus = 0;
            $totalGaji = ($gajiHarian * $jumlahHadir) + $tunjanganJabatan + $thr + $bonus;

            $payroll = Payroll::create([
                'pegawai_id' => $employee->id,
                'month' => $month,
                'year' => $year,
                'jumlah_hadir' => $jumlahHadir,
                'gaji_harian' => $gajiHarian,
                'tunjangan_jabatan' => $tunjanganJabatan,
                'thr_days' => $thrDays,
                'thr' => $thr,
                'bonus' => $bonus,
                'total_gaji' => $totalGaji,
                'status' => 'pending',
                'generated_by' => Auth::id(),
            ]);

            // Notify Employee (Database & Email with PDF)
            $employee->notify(new \App\Notifications\PayrollGeneratedNotification($payroll));

            $countGenerated++;
        }

        return redirect()->route('admin.payroll.index', ['month' => $month, 'year' => $year])
            ->with('success', "Berhasil men-generate $countGenerated data payroll.");
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

        $thr = $payroll->gaji_harian * $request->thr_days;

        $totalGaji = ($payroll->gaji_harian * $payroll->jumlah_hadir) +
            $payroll->tunjangan_jabatan +
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

        $payrolls = Payroll::where('month', $month)
            ->where('year', $year)
            ->get();

        $count = 0;
        foreach ($payrolls as $payroll) {
            $thr = $payroll->gaji_harian * $request->thr_days;
            $totalGaji = ($payroll->gaji_harian * $payroll->jumlah_hadir) +
                $payroll->tunjangan_jabatan +
                $thr +
                $request->bonus;

            $payroll->update([
                'thr_days' => $request->thr_days,
                'thr' => $thr,
                'bonus' => $request->bonus,
                'keterangan_bonus' => $request->keterangan_bonus,
                'total_gaji' => $totalGaji,
            ]);
            $count++;
        }

        return redirect()->route('admin.payroll.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', "Berhasil memperbarui $count data payroll secara massal.");
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Data payroll berhasil dihapus.');
    }
}
