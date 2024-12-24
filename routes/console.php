<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\SendWeatherNotificationsJob;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

app()->singleton(Schedule::class, function ($app) {
    $schedule = $app->make(Schedule::class);

    // Dispatch the job hourly
    $schedule->call(function () {
        SendWeatherNotificationsJob::dispatch();
    })->hourly()->onOneServer();

    return $schedule;
});
