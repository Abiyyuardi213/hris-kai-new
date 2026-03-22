<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMonthlyPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payroll automatically for all employees for the previous month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payroll generation...');

        // If generated on June 1st, we calculate for May
        $targetDate = now()->subMonth();
        $month = $targetDate->month;
        $year = $targetDate->year;

        $this->info("Target Period: " . $targetDate->format('F Y'));

        $employees = \App\Models\Pegawai::with('jabatan')->get();
        $systemUser = \App\Models\User::whereHas('role', function($q) {
            $q->where('role_name', 'Admin');
        })->first() ?? \App\Models\User::first();

        if (!$systemUser) {
            $this->error('No admin user found to associate with payroll generation.');
            return 1;
        }

        $countGenerated = 0;

        foreach ($employees as $employee) {
            // Check if already generated
            $exists = \App\Models\Payroll::where('pegawai_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) continue;

            if (!$employee->jabatan) {
                $this->warn("Skipping {$employee->nama_lengkap}: No position (jabatan) assigned.");
                continue;
            }

            // Count attendances for that month
            $jumlahHadir = \App\Models\Presensi::where('pegawai_id', $employee->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->whereNotNull('jam_masuk')
                ->count();

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

            // For auto-generate, THR and Bonus are 0 by default
            $thrDays = 0;
            $thr = 0;
            $bonus = 0;

            $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganPerumahan + $tunjanganAdminBank + $tunjanganJpk + $tunjanganPajak + $erJKK + $erJHT + $erJKM + $tunjanganJpkPensiun + $tunjanganJpBpjs + $thr + $bonus;

            $payroll = \App\Models\Payroll::create([
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
                'generated_by' => $systemUser->id,
            ]);

            // Notify Employee (Database & Email with PDF)
            $employee->notify(new \App\Notifications\PayrollGeneratedNotification($payroll));

            $countGenerated++;
        }

        $this->info("Successfully generated $countGenerated payroll records.");
        return 0;
    }
}
