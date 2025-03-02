<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('fetch:news', function () {
    $this->comment('Fetching news...');
})->describe('Fetch news from the News API');

app()->resolving(Schedule::class, function (Schedule $schedule) {
    $schedule->command('fetch:news')->hourly();
});
