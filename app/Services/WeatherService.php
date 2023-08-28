<?php

namespace App\Services;

use App\Dto\WeatherDto;
use App\Enums\WeatherSource;
use App\Sources\WeatherSourceFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WeatherService implements Contracts\WeatherService
{
    public function getSources(): Collection
    {
        $sources = new Collection();

        foreach (WeatherSource::cases() as $case) {
            $sourceClass = WeatherSourceFactory::make($case->value);
            $sources->add($sourceClass);
        }

        return $sources;
    }

    public function getWeatherBySourceAndCity(string $source, string $city): WeatherDto
    {
        $cacheId = $this->getWeatherCacheKey($source, $city);
        $data = Cache::get($cacheId);

        if ($data) {
            return $data;
        }

        $sourceClass = WeatherSourceFactory::make($source);
        $weather = $sourceClass->getWeatherByCity($city);

        Cache::put($cacheId, $weather, config('weather.cache_time'));
        return $weather;
    }

    public function getAggregateWeatherByCity(string $city): WeatherDto
    {
        $sources = $this->getSources();

        $weatherCollection = new Collection();
        foreach ($sources as $source) {
            $weather = $source->getWeatherByCity($city);
            $weatherCollection->add($weather);
        }

        return new WeatherDto(
            round($weatherCollection->avg('temperature'), 2),
            round($weatherCollection->avg('humidity')),
            round($weatherCollection->avg('pressure'), 2)
        );
    }

    private function getWeatherCacheKey(string $source, string $city): string
    {
        return 'weather_' . $source . '_city_' . $city;
    }
}