<?php

namespace App\Http\Controllers;

use App\Services\OwmService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

    /**
     * Returns today weather
     *
     * @param  \Illuminate\Http\Request $request float|int|string 'lat', 'long' or 'location' string required
     * @return string error with error message in case of error and status 400 or current weather JSON object with status 200
     */
    public function getTodayWeatherAPI(Request $request, OwmService $owmService)
    {
        $weather = false;

        if ($request->has('lat') && $request->has('long')) {
            $lat = $request->input('lat');
            $long = $request->input('long');

            $weather = $owmService->getTodayWeatherByCoords($lat, $long);
        }

        if ($request->has('location')) {
            $location = $request->input('location');

            $weather = $owmService->getTodayWeatherByLocation($location);
        }

        if ($weather !== false) {
            return response()->json(['weather' => $weather]);
        } else {
            return response()->json(['error' => 'Unable to get weather for specified location'], 400);
        }
    }

}
