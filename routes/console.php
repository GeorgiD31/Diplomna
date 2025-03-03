<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;


app()->resolving(Schedule::class, function (Schedule $schedule) {
    $schedule->command('fetch:news')->hourly();
});
