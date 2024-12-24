<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_weather_service_and_controller_integration()
    {
        // Mock WeatherService to use real HTTP requests
        $weatherService = new WeatherService();
        $this->app->instance(WeatherService::class, $weatherService);

        // Call the endpoint
        $response = $this->getJson('/weather/London');

        // Assert the structure of the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'city',
                'precipitation',
                'uv_index',
            ]);
    }
}
