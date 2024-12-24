<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
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

    protected function buildUrl($endpoint)
    {
        $base = rtrim($this->apiUrl, '/');
        $endpoint = ltrim($endpoint, '/');
        $url = "{$base}/{$endpoint}";
        Log::info("Constructed full URL: {$url}");
        return $url;
    }

    /**
     * Fetch current weather data for a city.
     */
    public function getCurrentWeather($city)
    {
        $url = $this->buildUrl('current.json');

        Log::info("Making request to URL: {$url} with city: {$city}");

        $response = Http::get($url, [
            'key' => $this->apiKey,
            'q' => $city,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Weather API request failed', [
            'url' => $url,
            'city' => $city,
            'response' => $response->body(),
        ]);

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
