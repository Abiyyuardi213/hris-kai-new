<?php

namespace App\Services;

use App\Models\Presensi;
use Carbon\Carbon;

class AttendanceAutoCheckoutService
{
    public function run(?Carbon $now = null): array
    {
        $now = $now ?: Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $cutoffTime = '23:00:00';

        $query = Presensi::with('shift')
            ->where('status', 'Hadir')
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->where(function ($query) use ($today, $now) {
                $query->whereDate('tanggal', '<', $today);

                if ($now->format('H:i:s') >= '23:00:00') {
                    $query->orWhereDate('tanggal', $today);
                }
            });

        $processed = 0;

        $query->chunkById(100, function ($presensis) use (&$processed, $cutoffTime) {
            foreach ($presensis as $presensi) {
                $presensi->forceFill([
                    'jam_pulang' => $cutoffTime,
                    'pulang_cepat' => 0,
                    'lokasi_pulang' => $presensi->lokasi_pulang ?: 'Auto checkout by system',
                    'keterangan' => $this->autoCheckoutNote($presensi->keterangan),
                ])->save();

                $processed++;
            }
        });

        return [
            'processed' => $processed,
            'checkout_time' => $cutoffTime,
        ];
    }

    private function autoCheckoutNote(?string $current): string
    {
        $note = 'Auto checkout 23:00 karena pegawai belum check-out.';

        if (!$current) {
            return $note;
        }

        if (str_contains($current, 'Auto checkout 23:00')) {
            return $current;
        }

        return $current . ' | ' . $note;
    }
}
