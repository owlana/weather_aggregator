<?php

namespace App\Dto;

final readonly class WeatherDto extends Contract\BaseDto
{
    /**
     * @param float $temperature
     * @param int $humidity
     * @param float $pressure
     */
    public function __construct(
        public float $temperature,
        public int $humidity,
        public float $pressure
    ) {
    }
}
