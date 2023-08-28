<?php

namespace App\Sources;

use App\Dto\WeatherDto;
use App\Enums\WeatherSource;
use App\Sources\Contracts\SourceBase;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final class WeatherApiSource extends SourceBase
{
    private string $apiToken = '';
    private const BASE_URL = 'http://api.weatherapi.com/v1';
    protected WeatherSource $code = WeatherSource::WeatherApi;

    public function __construct()
    {
        $this->apiToken = config('weather.source.weatherApi.api_key');

        if (!$this->apiToken) {
            throw new Exception('WeatherApi: не задан токен API');
        }
    }

    public function getTitle(): string
    {
        return 'WeatherAPI';
    }

    public function getWeatherByCity(string $city): WeatherDto
    {
        $response = $this->request(
            '/current.json',
            [
                'key' => $this->apiToken,
                'q' => $city
            ]
        );

        if ($response->getStatusCode() >= 300) {
            throw new Exception('WeatherApi: ошибка API');
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['current']['temp_c']) || !isset($data['current']['humidity']) || !isset($data['current']['pressure_mb'])) {
            throw new Exception('WeatherApi: недостаточно данных');
        }

        return new WeatherDto(
            (float)$data['current']['temp_c'],
            (int)$data['current']['humidity'],
            (float)$data['current']['pressure_mb']
        );
    }

    private function request(string $url, array $data): ResponseInterface
    {
        $client = new Client([
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
                'Access-Token' => $this->apiToken
            ],
            'timeout' => 3,
            'connect_timeout' => 2,
        ]);

        $queryString = http_build_query($data);

        return $client->request(
            'get',
            self::BASE_URL . $url . '?' . $queryString
        );
    }

}
