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

            $gajiHarian = $employee->jabatan->gaji_per_hari;
            $tunjanganJabatan = $employee->jabatan->tunjangan;

            // For auto-generate, THR and Bonus are 0 by default
            $thrDays = 0;
            $thr = 0;
            $bonus = 0;

            $totalGaji = ($gajiHarian * $jumlahHadir) + $tunjanganJabatan + $thr + $bonus;

            $payroll = \App\Models\Payroll::create([
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
