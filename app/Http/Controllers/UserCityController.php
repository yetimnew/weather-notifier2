<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class UserCityController extends Controller
{
    /**
     * Show the form for managing cities and thresholds.
     */
    public function index()
    {
        $user = auth()->user();
        $cities = $user->cities()->withPivot('uv_threshold', 'precipitation_threshold')->get();

        // dd($cities);

        return view('users.cities.index', compact('cities'));
    }

    /**
     * Add a city with thresholds for the authenticated user.
     */
    public function addCity(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'city_name' => 'required|string|max:255',
            'uv_threshold' => 'required|numeric|min:0|max:11',
            'precipitation_threshold' => 'required|numeric|min:0|max:100',
        ]);

        $city = City::firstOrCreate(['name' => $validated['city_name']]);

        // Attach the city to the user with thresholds
        auth()->user()->cities()->syncWithoutDetaching([
            $city->id => [
                'uv_threshold' => $validated['uv_threshold'],
                'precipitation_threshold' => $validated['precipitation_threshold'],
            ],
        ]);

        return redirect()->route('user.cities.index')->with('success', 'City and thresholds added successfully!');
    }

    /**
     * Update thresholds for an existing city.
     */
    public function updateThresholds(Request $request, $cityId)
    {
        $validated = $request->validate([
            'uv_threshold' => 'required|numeric|min:0|max:11',
            'precipitation_threshold' => 'required|numeric|min:0|max:100',
        ]);

        // Update the pivot table
        auth()->user()->cities()->updateExistingPivot($cityId, $validated);

        return redirect()->route('user.cities.index')->with('success', 'Thresholds updated successfully!');
    }



    /**
     * Remove a city from the user's preferences.
     */
    public function removeCity($cityId)
    {
        auth()->user()->cities()->detach($cityId);

        return redirect()->route('user.cities.index')->with('success', 'City removed successfully!');
    }
}
