<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // ✅ Check popular users EVERY MINUTE
        $schedule->command('users:check-popular')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/popular-users-cron.log'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
