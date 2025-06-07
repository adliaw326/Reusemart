<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckPendingTransactions;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Tambahkan commandmu di sini, misalnya:
        $this->info('Pengecekan TRANSAKSI PENDING OTEWEY');
        $schedule->command('transactions:check-pending')->everyMinute();
        $schedule->call(function () {
            Log::info('Scheduler running at ' . now());
        })->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
