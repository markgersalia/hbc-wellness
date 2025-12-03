<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string|string>
     */
    protected $commands = [
        // Optional: register your commands manually
        \App\Console\Commands\SendBookingReminders::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // -----------------------------
        // Local testing (runs every minute)
        // -----------------------------
        if (app()->environment('local')) {
            $schedule->command('booking:reminders')->everySecond()
                ->withoutOverlapping()
                ->runInBackground()
                ->onOneServer();
        }

        // -----------------------------
        // Production schedule (every 2 mins as example)
        // Adjust weekdays(), between(), timezone() as needed
        // -----------------------------
        if (app()->environment('production')) {
            $schedule->command('booking:reminders')
                ->everyTwoMinutes()
                ->timezone('Asia/Manila')       // Adjust your timezone 
                ->between('08:00', '17:00')    // Optional: working hours
                ->withoutOverlapping()
                ->runInBackground()
                ->onOneServer();
        }
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
