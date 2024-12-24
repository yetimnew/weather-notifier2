<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="size-6 stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                            Register Thresholds and Cities
                        </h2>
                    </div>
                    {{-- @extends('layouts.app') --}}
                    <div class="px-6 sm:px-8 lg:px-12">
                        <!-- Main Content Container -->
                        <div
                            class="container mx-auto mt-10 max-w-4xl bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-8 text-center">
                                Manage Cities and Weather Thresholds
                            </h2>

                            <!-- Add New City Form Section -->
                            <div class="mb-8 bg-gray-100 dark:bg-gray-700 p-6 rounded-md shadow">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add New City</h3>
                                <form method="POST" action="{{ route('user.cities.add') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="city_name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            City Name
                                        </label>
                                        <input type="text" name="city_name" id="city_name" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                    <div class="mb-4">
                                        <label for="uv_threshold"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            UV Index Threshold
                                        </label>
                                        <input type="number" step="0.1" name="uv_threshold" id="uv_threshold"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                    <div class="mb-4">
                                        <label for="precipitation_threshold"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Precipitation Threshold (mm)
                                        </label>
                                        <input type="number" step="0.1" name="precipitation_threshold"
                                            id="precipitation_threshold" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    </div>

                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white font-medium rounded-md shadow-md hover:bg-green-700">
                                        Save New City
                                    </button>
                                </form>
                            </div>

                            <!-- City Thresholds Table Section -->
                            <div class="overflow-x-auto">
                                <table
                                    class="table-auto border-collapse w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                City Name</th>
                                            <th
                                                class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                UV Index Threshold</th>
                                            <th
                                                class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Precipitation Threshold (mm)</th>
                                            <th
                                                class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $city)
                                            @if ($city)
                                                <tr class="border-t border-gray-300 dark:border-gray-600">
                                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $city->name ?? '' }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <form method="POST"
                                                            action="{{ route('user.cities.update', $city->id) }}"
                                                            class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="number" step="0.1" name="uv_threshold"
                                                                value="{{ $city->pivot->uv_threshold ?? '' }}" required
                                                                class="w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm" />
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <input type="number" step="0.1"
                                                            name="precipitation_threshold"
                                                            value="{{ $city->pivot->precipitation_threshold ?? '' }}"
                                                            required
                                                            class="w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm" />
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <button type="submit"
                                                            class="px-4 py-1 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">Update</button>
                                                        </form>
                                                        <form method="POST"
                                                            action="{{ route('user.cities.remove', $city->id) }}"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-4 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-md ml-2">Remove</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @else
                                                <p>Invalid city data.</p>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
