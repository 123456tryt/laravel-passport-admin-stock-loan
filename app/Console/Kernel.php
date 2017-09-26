<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

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
        // $schedule->command('inspire')
        //          ->hourly();

        //将redis中post api记录写入数据库
        $schedule->call(function () {
            $dataList = Redis::command("lrange", ["postApiLog", 0, -1]);
            $datas = [];
            foreach ($dataList as $v) {
                $datas[] = ["content" => $v];
            }
            DB::table("post_api_log")->insert($datas);
            Redis::command("ltrim", ["postApiLog", 1, 0]);
        })->dailyAt("22:00");
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
