<?php

namespace App\Services\Contracts;

use App\Dto\WeatherDto;
use Illuminate\Support\Collection;

interface WeatherService
{
    public function getSources(): Collection;

    public function getWeatherBySourceAndCity(string $source, string $city): WeatherDto;

    public function getAggregateWeatherByCity(string $city): WeatherDto;
}
