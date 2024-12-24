<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WeatherServiceTest extends TestCase
{
    protected WeatherService $weatherService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weatherService = new WeatherService();
    }

    public function test_build_url_constructs_correct_url()
    {
        $reflection = new \ReflectionClass(WeatherService::class);
        $method = $reflection->getMethod('buildUrl');
        $method->setAccessible(true);

        config(['services.weatherapi.url' => 'http://api.weatherapi.com/v1']);
        $result = $method->invoke($this->weatherService, 'current.json');

        $this->assertEquals('http://api.weatherapi.com/v1/current.json', $result);
    }

    public function test_get_current_weather_returns_valid_data()
    {
        Http::fake([
            'http://api.weatherapi.com/v1/current.json*' => Http::response([
                'current' => [
                    'uv' => 5.5,
                    'precip_mm' => 2.3,
                ],
            ], 200),
        ]);

        $data = $this->weatherService->getCurrentWeather('London');

        $this->assertNotNull($data);
        $this->assertEquals(5.5, $data['current']['uv']);
        $this->assertEquals(2.3, $data['current']['precip_mm']);
    }

    public function test_get_current_weather_logs_error_on_failure()
    {
        // Mock an HTTP response with a 500 error
        Http::fake([
            'http://api.weatherapi.com/v1/current.json*' => Http::response(null, 500),
        ]);

        // Mock the Log facade for the "Constructed full URL" message
        Log::shouldReceive('info')
            ->once()
            ->with('Constructed full URL: http://api.weatherapi.com/v1/current.json');

        // Mock the Log facade for the "Making request" message
        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'Making request to URL');
            });

        // Mock the Log facade for the error log
        Log::shouldReceive('error')
            ->once()
            ->with('Weather API request failed', \Mockery::on(function ($logData) {
                return isset($logData['url'], $logData['city'], $logData['response']);
            }));

        // Call the service method
        $data = $this->weatherService->getCurrentWeather('London');

        // Assert that the method returns null
        $this->assertNull($data, 'Expected getCurrentWeather to return null on failure.');
    }


    public function test_get_uv_index_returns_correct_value()
    {
        Http::fake([
            'http://api.weatherapi.com/v1/current.json*' => Http::response([
                'current' => [
                    'uv' => 6.0,
                ],
            ], 200),
        ]);

        $uvIndex = $this->weatherService->getUVIndex('London');
        $this->assertEquals(6.0, $uvIndex);
    }

    public function test_get_precipitation_returns_correct_value()
    {
        Http::fake([
            'http://api.weatherapi.com/v1/current.json*' => Http::response([
                'current' => [
                    'precip_mm' => 3.5,
                ],
            ], 200),
        ]);

        $precipitation = $this->weatherService->getPrecipitation('London');
        $this->assertEquals(3.5, $precipitation);
    }

    public function test_build_url_logs_correct_url()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Constructed full URL: http://api.weatherapi.com/v1/current.json');

        config(['services.weatherapi.url' => 'http://api.weatherapi.com/v1']);
        $reflection = new \ReflectionClass(WeatherService::class);
        $method = $reflection->getMethod('buildUrl');
        $method->setAccessible(true);

        $method->invoke($this->weatherService, 'current.json');
    }

    public function test_weather_service_handles_timeout()
    {
        Http::fake([
            '*' => Http::response([], 504),
        ]);

        $data = $this->weatherService->getCurrentWeather('Addis Ababa');
        $this->assertNull($data);
    }

    public function test_weather_service_handles_invalid_api_key()
    {
        Http::fake([
            '*' => Http::response(['error' => 'Invalid API Key'], 401),
        ]);

        $data = $this->weatherService->getCurrentWeather('Addis Ababa');
        $this->assertNull($data);
    }

    // public function test_weather_service_handles_malformed_response()
    // {
    //     Http::fake([
    //         '*' => Http::response(['unexpected_key' => 'unexpected_value'], 200),
    //     ]);

    //     $data = $this->weatherService->getCurrentWeather('Addis Ababa');
    //     $this->assertNull($data);
    // }
}
