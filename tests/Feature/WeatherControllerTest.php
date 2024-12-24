<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    public function test_show_weather_returns_correct_data()
    {
        Http::fake([
            'weatherapi.com/*' => Http::response([
                'current' => [
                    'uv' => 7.5,
                    'precip_mm' => 12.3,
                ],
            ], 200),
        ]);

        $response = $this->getJson('/weather/London');

        $response->assertStatus(200)
            ->assertJson([
                'city' => 'London',
                'precipitation' => 12.3,
                'uv_index' => 7.5,
            ]);
    }

    public function test_show_weather_handles_api_failure()
    {
        Http::fake([
            'weatherapi.com/*' => Http::response([], 500),
        ]);

        $response = $this->getJson('/weather/London');

        $response->assertStatus(200)
            ->assertJson([
                'city' => 'London',
                'precipitation' => null,
                'uv_index' => null,
            ]);
    }
}
