<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for popular users every hour
        // $schedule->command('users:check-popular')
        //     ->hourly()
        //     ->withoutOverlapping()
        //     ->appendOutputTo(storage_path('logs/popular-users.log'));
        $schedule->command('users:check-popular')->everyMinute()
                ->appendOutputTo(storage_path('logs/popular-users.log'));

        // Alternative: Run every day at midnight
        // $schedule->command('users:check-popular')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
