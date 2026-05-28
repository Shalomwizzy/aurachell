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
        // Daily DB + images backup at 2am
        $schedule->command('backup:run --only-db')->dailyAt('02:00');
        $schedule->command('backup:clean')->weekly();

        // Paystack reconciliation: every 30 minutes
        $schedule->command('paystack:reconcile')->everyThirtyMinutes();

        // Release expired stock reservations: every 5 minutes
        $schedule->command('stock:clear-reservations')->everyFiveMinutes();

        // Cart abandonment: daily at 10am
        $schedule->command('emails:cart-reminder')->dailyAt('10:00');

        // Wishlist reminder: every Monday at 9am
        $schedule->command('emails:wishlist-reminder')->weeklyOn(1, '09:00');

        // Happy new month: 1st of each month at 8am
        $schedule->command('emails:new-month')->monthlyOn(1, '08:00');

        // Birthday emails: daily at 9am
        $schedule->command('emails:birthday')->dailyAt('09:00');

        // Review request: daily at 11am (7 days post-delivery)
        $schedule->command('emails:review-request')->dailyAt('11:00');

        // Reorder reminder: daily at 1pm (60 days since last delivery)
        $schedule->command('emails:reorder-reminder')->dailyAt('13:00');
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
