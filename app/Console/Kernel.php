<?php

namespace App\Console;

use DB;
use Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use ICanBoogie\DateTime;
use App\Notification;
use App\ContactTrip;
use App\Profile;
use App\User;
use App\Http\Helper;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {Notification::checkEveryMinute();})->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');

        Artisan::command('discord {message}', function ($message) {
            Helper::message($message);
        });

        Artisan::command('minute', function () {
            Notification::checkEveryMinute();
        });
    }
}
