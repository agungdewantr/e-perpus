<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update-exp-pengambilan-buku')->daily();
        $schedule->command('update-exp-peminjaman-buku')->daily();
        $schedule->command('update-exp-perpanjangan-buku')->daily();

        $schedule->command('notifikasi-pengembalian-buku-h-min-2')->daily();
        $schedule->command('notifikasi-pengembalian-buku-h-min-1')->daily();
        $schedule->command('notifikasi-pengembalian-buku-h-plus-1')->daily();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
