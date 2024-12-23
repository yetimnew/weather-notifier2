<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function showWeather($city)
    {
        $precipitation = $this->weatherService->getPrecipitation($city);
        $uvIndex = $this->weatherService->getUVIndex($city);

        return response()->json([
            'city' => $city,
            'precipitation' => $precipitation,
            'uv_index' => $uvIndex,
        ]);
    }
}
