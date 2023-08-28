<?php

return [
    'source' => [
        'tomorrow' => [
            'api_key' => env('WEATHER_TOMORROW_API_KEY'),
        ],
        'weatherApi' => [
            'api_key' => env('WEATHER_API_API_KEY'),
        ],
    ],
    'cache_time' => 60
];
