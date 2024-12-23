<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/weather/{city}', [WeatherController::class, 'showWeather'])->name('weather.show');
});
