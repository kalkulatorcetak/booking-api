<?php

namespace App\Console;

use App\Console\Commands\TokenGenerateCommand;
use Illuminate\Console\Scheduling\Schedule;
use JK\Dingo\Api\Console\Commands\RouteListCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        RouteListCommand::class,
        TokenGenerateCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
    }
}
