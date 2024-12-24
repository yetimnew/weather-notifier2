<?php

namespace App\Jobs;

use App\Models\City;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeatherAlertNotification;

class SendWeatherNotificationsJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Start of job processing
        Log::info('Starting SendWeatherNotificationsJob.');

        // Resolve WeatherService within the handle method
        $weatherService = app(WeatherService::class);

        // Fetch all monitored cities with their users
        $cities = City::with('users')->get();
        Log::info('Fetched cities with users', ['city_count' => $cities->count()]);

        foreach ($cities as $city) {
            Log::info('Processing city', ['city' => $city->name]);

            // Fetch weather data for the city
            $precipitation = $weatherService->getPrecipitation($city->name);
            $uvIndex = $weatherService->getUVIndex($city->name);

            // Log weather data
            Log::info('Weather data fetched', [
                'city' => $city->name,
                'precipitation' => $precipitation,
                'uv_index' => $uvIndex,
            ]);

            // Skip if weather data is unavailable
            if (is_null($precipitation) || is_null($uvIndex)) {
                Log::warning('Weather data unavailable for city', ['city' => $city->name]);
                continue;
            }

            foreach ($city->users as $user) {
                $sendNotification = false;
                $message = "Weather Alert for {$city->name}:\n";

                // Extract thresholds from the pivot table
                $precipitationThreshold = $user->pivot->precipitation_threshold ?? null;
                $uvThreshold = $user->pivot->uv_threshold ?? null;

                Log::info('User thresholds', [
                    'user_id' => $user->id,
                    'precipitation_threshold' => $precipitationThreshold,
                    'uv_threshold' => $uvThreshold,
                ]);

                // Check thresholds
                if ($precipitation > $precipitationThreshold) {
                    $sendNotification = true;
                    $message .= "Precipitation: " . $precipitation . "mm (Threshold: " . $precipitationThreshold . "mm)\n";
                }

                if ($uvIndex > $uvThreshold) {
                    $sendNotification = true;
                    $message .= "UV Index: " . $uvIndex . " (Threshold: " . $uvThreshold . ")\n";
                }

                // If a notification is needed, send it
                if ($sendNotification) {
                    $message .= "\nPlease take precautions. Stay safe!";
                    Notification::send($user, new WeatherAlertNotification($city->name, $precipitation, $uvIndex, $message));

                    Log::info('Notification sent', [
                        'user_id' => $user->id,
                        'city' => $city->name,
                        'precipitation' => $precipitation,
                        'uv_index' => $uvIndex,
                    ]);
                } else {
                    Log::info('No notification needed', ['user_id' => $user->id, 'city' => $city->name]);
                }
            }
        }

        // End of job processing
        Log::info('SendWeatherNotificationsJob completed.');
    }
}
