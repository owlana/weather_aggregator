<?php

namespace App\Sources\Contracts;

use App\Dto\WeatherDto;
use App\Enums\WeatherSource;

abstract class SourceBase
{
    protected WeatherSource $code;

    public function getCode(): string
    {
        return $this->code->value;
    }

    abstract public function getTitle(): string;

    abstract public function getWeatherByCity(string $city): WeatherDto;
}
