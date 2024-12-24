<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Models\City;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeatherAlertNotification;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function showWeather($cityName)
    {
        // Fetch weather data
        $precipitation = $this->weatherService->getPrecipitation($cityName);
        $uvIndex = $this->weatherService->getUVIndex($cityName);

        // Find the city in the database
        $city = City::where('name', $cityName)->first();

        if ($city) {
            // Get users monitoring this city
            $users = $city->users;
            foreach ($users as $user) {
                $sendNotification = false;
                $message = "Weather Alert for {$city->name}:\n";

                // Extract pivot data into local variables
                $precipitationThreshold = $user->pivot->precipitation_threshold ?? null;
                $uvThreshold = $user->pivot->uv_threshold ?? null;

                // Check thresholds
                if ($precipitation > $precipitationThreshold) {
                    $sendNotification = true;
                    $message .= "Precipitation: " . $precipitation . "mm (Threshold: " . $precipitationThreshold . "mm)\n";
                }

                if ($uvIndex > $uvThreshold) {
                    $sendNotification = true;
                    $message .= "UV Index: " . $uvIndex . " (Threshold: " . $uvThreshold . ")\n";
                }

                if ($sendNotification) {
                    $message .= "\nPlease take precautions. Stay safe!";
                    Notification::send($user, new WeatherAlertNotification($city->name, $precipitation, $uvIndex, $message));
                }
            }
        }

        return response()->json([
            'city' => $cityName,
            'precipitation' => $precipitation,
            'uv_index' => $uvIndex,
        ]);
    }
}
