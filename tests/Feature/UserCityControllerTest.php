<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\City;
use App\Models\User;
use Laravel\Jetstream\Team;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCityControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method to show cities and thresholds.
     */
    public function test_user_can_view_managed_cities()
    {
        // Create a test user with all necessary relationships
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        // Ensure the user has a team if required by the navigation menu
        $user->currentTeam()->associate(Team::factory()->create());
        $user->save();

        // Create and attach cities to the user
        $cities = City::factory()->count(3)->create();
        $user->cities()->attach($cities, [
            'uv_threshold' => 5.5,
            'precipitation_threshold' => 10.0,
        ]);

        // Act as the authenticated user
        $this->actingAs($user);

        // Ensure cities are attached
        $this->assertCount(3, $user->cities);

        // Send GET request to index route
        $response = $this->get(route('user.cities.index'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert cities are displayed
        foreach ($cities as $city) {
            $response->assertSee($city->name);
            $response->assertSee('5.5');
            $response->assertSee('10.0');
        }
    }


    /**
     * Test the addCity method to add a new city and thresholds.
     */
    public function test_user_can_add_city_with_thresholds()
    {
        // Create a test user
        $user = User::factory()->create();

        // Act as the authenticated user
        $this->actingAs($user);

        // Send a POST request to add a city
        $response = $this->post(route('user.cities.add'), [
            'city_name' => 'New York',
            'uv_threshold' => 6.0,
            'precipitation_threshold' => 15.0,
        ]);

        // Assert that the response redirects with a success message
        $response->assertRedirect(route('user.cities.index'))
            ->assertSessionHas('success', 'City and thresholds added successfully!');

        // Assert that the city was added to the database and attached to the user
        $this->assertDatabaseHas('cities', ['name' => 'New York']);
        $this->assertDatabaseHas('city_user', [
            'user_id' => $user->id,
            'uv_threshold' => 6.0,
            'precipitation_threshold' => 15.0,
        ]);
    }

    /**
     * Test the updateThresholds method to update thresholds for an existing city.
     */
    public function test_user_can_update_city_thresholds()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a city and attach it to the user with initial thresholds
        $city = City::factory()->create();
        $user->cities()->attach($city, [
            'uv_threshold' => 5.5,
            'precipitation_threshold' => 10.0,
        ]);

        $this->actingAs($user);

        // Send a PUT request to update the thresholds
        $response = $this->put(route('user.cities.update', $city->id), [
            'uv_threshold' => 8.0,
            'precipitation_threshold' => 20.0,
        ]);

        // Assert that the response redirects with a success message
        $response->assertRedirect(route('user.cities.index'))
            ->assertSessionHas('success', 'Thresholds updated successfully!');

        // Assert that the thresholds were updated in the pivot table
        $this->assertDatabaseHas('city_user', [
            'user_id' => $user->id,
            'city_id' => $city->id,
            'uv_threshold' => 8.0,
            'precipitation_threshold' => 20.0,
        ]);
    }

    /**
     * Test the removeCity method to detach a city from the user.
     */
    public function test_user_can_remove_city()
    {
        // Create a test user and city
        $user = User::factory()->create();
        $city = City::factory()->create();

        // Attach the city to the user
        $user->cities()->attach($city);

        // Act as the authenticated user
        $this->actingAs($user);

        // Send a DELETE request to remove the city
        $response = $this->delete(route('user.cities.remove', $city->id));

        // Assert that the response redirects with a success message
        $response->assertRedirect(route('user.cities.index'))
            ->assertSessionHas('success', 'City removed successfully!');

        // Assert that the city is no longer attached to the user
        $this->assertDatabaseMissing('city_user', [
            'user_id' => $user->id,
            'city_id' => $city->id,
        ]);
    }

    public function test_add_city_validation_failure()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('user.cities.add'), [
            'city_name' => '',
            'uv_threshold' => 'invalid',
            'precipitation_threshold' => 200,
        ]);

        $response->assertSessionHasErrors(['city_name', 'uv_threshold', 'precipitation_threshold']);
    }

    // public function test_prevent_duplicate_city_addition()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $city = City::factory()->create(['name' => 'Addis Ababa']);
    //     $user->cities()->attach($city, ['uv_threshold' => 5.0, 'precipitation_threshold' => 10.0]);

    //     $response = $this->post(route('user.cities.add'), [
    //         'city_name' => 'Addis Ababa',
    //         'uv_threshold' => 6.0,
    //         'precipitation_threshold' => 12.0,
    //     ]);

    //     $response->assertSessionHasErrors(['city_name']);
    // }
}
