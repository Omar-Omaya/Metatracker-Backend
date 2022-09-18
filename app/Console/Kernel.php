<?php
    
    namespace App\Console;
         
    use Illuminate\Console\Scheduling\Schedule;
    use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

    class Kernel extends ConsoleKernel
    {
        /**
         * The Artisan commands provided by your application.
         *
         * @7 array
         */
        protected $commands = [
            Commands\LogCron::class,
            Commands\AbsenceDays::class,
            Commands\ManageShift::class,


        ];
          
        /**
         * Define the application's command schedule.
         *
         * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
         * @return void
         */
        protected function schedule(Schedule $schedule)
        {
            $schedule->command('log:cron')
                     ->everyMinute();
            $schedule->command('abs:days')
                     ->everyMinute();
            $schedule->command('manage:shift')
                    ->everyMinute();

            // $schedule->call(function (){
            //     $history = DB::table('histories')->get();
            //     Log::info($history);
            // })->everyMinute();

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