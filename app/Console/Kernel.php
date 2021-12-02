<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Cleanup\DeleteOldUnverifiedUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();
        $schedule->command('lcm:delete-old-unverified-users')->daily();
        // $schedule->command('lcm:post-article-to-twitter')->twiceDaily(12, 16);
        $schedule->command('lcm:post-article-to-telegram')->everyFourHours();
        $schedule->command('sitemap:generate')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
