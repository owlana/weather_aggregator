<?php

namespace App\Sources;

use App\Enums\WeatherSource;
use App\Sources\Contracts\SourceBase;
use InvalidArgumentException;

final class WeatherSourceFactory
{
    public static function make(string $service): SourceBase
    {
        $className = self::weatherSourceToClass()[$service] ?? false;

        if (!$className) {
            throw new InvalidArgumentException('Weather source "' . $service . '" not found');
        }

        return new $className();
    }

    private static function weatherSourceToClass(): array
    {
        return [
            WeatherSource::Tomorrow->value => TomorrowSource::class,
            WeatherSource::WeatherApi->value => WeatherApiSource::class,
        ];
    }
}
