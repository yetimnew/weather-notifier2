<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    public function test_get_current_weather_returns_correct_data()
    {
        Http::fake([
            'weatherapi.com/*' => Http::response([
                'current' => [
                    'uv' => 7.5,
                    'precip_mm' => 12.3,
                ],
            ], 200),
        ]);

        $weatherService = new WeatherService();
        $data = $weatherService->getCurrentWeather('London');

        $this->assertNotNull($data);
        $this->assertArrayHasKey('current', $data);
        $this->assertEquals(7.5, $data['current']['uv']);
        $this->assertEquals(12.3, $data['current']['precip_mm']);
    }

    public function test_get_uv_index_returns_correct_value()
    {
        Http::fake([
            'weatherapi.com/*' => Http::response([
                'current' => [
                    'uv' => 7.5,
                ],
            ], 200),
        ]);

        $weatherService = new WeatherService();
        $uvIndex = $weatherService->getUVIndex('London');

        $this->assertEquals(7.5, $uvIndex);
    }

    public function test_get_precipitation_returns_correct_value()
    {
        Http::fake([
            'weatherapi.com/*' => Http::response([
                'current' => [
                    'precip_mm' => 12.3,
                ],
            ], 200),
        ]);

        $weatherService = new WeatherService();
        $precipitation = $weatherService->getPrecipitation('London');

        $this->assertEquals(12.3, $precipitation);
    }
}
