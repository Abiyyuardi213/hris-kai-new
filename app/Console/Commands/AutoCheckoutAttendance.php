<?php

namespace App\Console\Commands;

use App\Services\AttendanceAutoCheckoutService;
use Illuminate\Console\Command;

class AutoCheckoutAttendance extends Command
{
    protected $signature = 'attendance:auto-checkout';

    protected $description = 'Automatically checkout employees who checked in but forgot to checkout by 23:00';

    public function handle(AttendanceAutoCheckoutService $service): int
    {
        $result = $service->run();

        $this->info("Auto checkout selesai. {$result['processed']} data presensi diproses dengan jam pulang {$result['checkout_time']}.");

        return self::SUCCESS;
    }
}
