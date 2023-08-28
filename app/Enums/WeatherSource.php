<?php

namespace App\Enums;

enum WeatherSource: string
{
    case Tomorrow = 'tomorrow';
    case WeatherApi = 'weatherApi';
}