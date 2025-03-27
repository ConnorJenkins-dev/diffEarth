<?php

// app/Http/Controllers/WeatherController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        // Get latitude and longitude from the request, fallback to defaults if not provided
        $lat = $request->input('lat', '51.491992'); // Default lat if not provided
        $lon = $request->input('lon', '-3.197266'); // Default lon if not provided

        $APIkey = 'a5cacdff2974f07a1b4f3ffbeb242566';
        $api_url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$APIkey";

        $response = Http::withOptions([
            'verify' => false, // Disable SSL certificate verification
        ])->get($api_url);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch weather data'], 500);
        }

        $weather = $response->json();
        $temp_in_celsius = $weather['main']['temp'] - 273.15;

        return response()->json([
            'temperature' => round($temp_in_celsius, 2),
            'condition' => $weather['weather'][0]['main'],
            'description' => $weather['weather'][0]['description'],
            'icon' => "https://openweathermap.org/img/wn/{$weather['weather'][0]['icon']}.png"
        ]);
    }
}
