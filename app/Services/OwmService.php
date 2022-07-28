<?php

namespace App\Services;

/**
 * Class OwmService.
 */
class OwmService
{
    /**
     * Returns today weather by location
     *
     * @param string $location Location
     * @return bool `false` in case of error or current weather JSON object
     */
    public function getTodayWeatherByLocation($location)
    {
        $httpClient = new \GuzzleHttp\Client();
        $request =
            $httpClient
                ->get("https://api.openweathermap.org/data/2.5/weather?q=" . $location . "&appid=" . config('apis.owm_code'));

        try {
            $response = json_decode($request->getBody()->getContents());
            if ($response->cod !== 200) {
                return false;
            }
        } catch (\Exception $e) {
            $response = false;
        }

        return $response;
    }

    /**
     * Returns today weather by latitude and longitude
     *
     * @param float|int|string $lat Latitude
     * @param float|int|string $long Longitude
     * @return bool `false` in case of error or current weather JSON object
     */
    public function getTodayWeatherByCoords($lat, $long)
    {
        if (!$this->validateLatitude($lat) || !$this->validateLongitude($long)) {
            return false;
        }

        $httpClient = new \GuzzleHttp\Client();
        $request =
            $httpClient
                ->get("https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $long . "&appid=" . config('apis.owm_code'));


        try {
            $response = json_decode($request->getBody()->getContents());
            if ($response->cod !== 200) {
                return false;
            }
        } catch (\Exception $e) {
            $response = false;
        }

        return $response;
    }

    /**
     * Validates a given latitude $lat
     *
     * @param float|int|string $lat Latitude
     * @return bool `true` if $lat is valid, `false` if not
     */
    private function validateLatitude($lat) {
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
    }

    /**
     * Validates a given longitude $long
     *
     * @param float|int|string $long Longitude
     * @return bool `true` if $long is valid, `false` if not
     */
    private function validateLongitude($long) {
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
    }
}
