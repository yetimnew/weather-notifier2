<div
    class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    {{-- <x-application-logo class="block h-12 w-auto" /> --}}

    <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
        Welcome to your our Weather Notifier !
    </h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
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

        <form method="POST" action="{{ route('user.cities.add') }}" class="mt-4">
            @csrf
            <div class="mb-4">
                <label for="city_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    City Name
                </label>
                <input type="text" name="city_name" id="city_name" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>

            <div class="mb-4">
                <label for="uv_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    UV Index Threshold
                </label>
                <input type="number" step="0.1" name="uv_threshold" id="uv_threshold" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>

            <div class="mb-4">
                <label for="precipitation_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Precipitation Threshold (mm)
                </label>
                <input type="number" step="0.1" name="precipitation_threshold" id="precipitation_threshold"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>

            <div class="mt-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Settings
                </button>
            </div>
        </form>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            You can add multiple cities and set thresholds for UV index and precipitation. These settings will trigger
            weather alerts for you.
        </p>
    </div>

</div>
