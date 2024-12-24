<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Jobs\SendWeatherNotificationsJob;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\UserCityController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-email', function () {
    // Sending a basic test email
    Mail::raw('This is a test email sent from Laravel using Mailtrap!', function ($message) {
        $message->to('yetimnew@gmial.com') // Replace with your recipient email
            ->subject('Test Email from Laravel');
    });

    return 'Test email sent! Check your Mailtrap inbox.';
});

Route::get('/test-weather-job', function () {
    SendWeatherNotificationsJob::dispatch();
    return 'Job dispatched!';
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/weather/{city}', [WeatherController::class, 'showWeather'])->name('weather.show');
    Route::get('/user/cities', [UserCityController::class, 'index'])->name('user.cities.index');
    Route::post('user/cities', [UserCityController::class, 'addCity'])->name('user.cities.add');
    Route::put('user/cities/{city}', [UserCityController::class, 'updateThresholds'])->name('user.cities.update');
    Route::delete('user/cities/{city}', [UserCityController::class, 'removeCity'])->name('user.cities.remove');
});
