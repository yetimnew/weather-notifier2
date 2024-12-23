<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.weatherapi.key', env('WEATHER_API_KEY'));
        $this->apiUrl = config('services.weatherapi.url', env('WEATHER_API_URL'));
    }

    /**
     * Fetch current weather data for a city.
     */
    public function getCurrentWeather($city)
    {
        $response = Http::get("{$this->apiUrl}/current.json", [
            'key' => $this->apiKey,
            'q' => $city,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    /**
     * Fetch UV index for a city.
     */
    public function getUVIndex($city)
    {
        $data = $this->getCurrentWeather($city);

        return $data['current']['uv'] ?? null;
    }

    /**
     * Fetch precipitation level for a city.
     */
    public function getPrecipitation($city)
    {
        $data = $this->getCurrentWeather($city);

        return $data['current']['precip_mm'] ?? null;
    }
}
