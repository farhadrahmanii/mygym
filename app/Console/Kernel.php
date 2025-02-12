<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Athlet;
use App\Models\Box;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $expiringAthletes = Athlet::where('admission_expiry_date', '<=', Carbon::now()->addDays(5))->get();
            foreach ($expiringAthletes as $athlete) {
                // Notify admin about expiring athlete fees
                // You can use your preferred notification method here
            }

            $expiringBoxes = Box::where('expire_date', '<=', Carbon::now()->addDays(5))->get();
            foreach ($expiringBoxes as $box) {
                // Notify admin about expiring boxes
                // You can use your preferred notification method here
            }
        })->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
