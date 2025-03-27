<?php

header('Content-Type: application/json');
$lat = '51.491992';
$lon = '-3.197266';
$APIkey = 'a5cacdff2974f07a1b4f3ffbeb242566';

$api_url = 'https://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon . '&appid=' . $APIkey;

$weather = json_decode(file_get_contents($api_url), true);

$temp = $weather['main']['temp'];

$temp_in_celsius = $temp - 273.15;

$temp_current = $weather['weather'][0]['main'];

$temp_current_desc = $weather['weather'][0]['description'];

$temp_current_icon = $weather[0]['icon'];


echo json_encode([
    'temperature' => round($temp_in_celsius, 2),
    'condition' => $temp_current,
    'description' => $temp_current_desc,
    'icon' => "https://openweathermap.org/img/wn/{$temp_current_icon}.png"
]);
