<?php

namespace App\Sources;

use App\Dto\WeatherDto;
use App\Enums\WeatherSource;
use App\Sources\Contracts\SourceBase;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final class TomorrowSource extends SourceBase
{
    private string $apiToken = '';
    private const BASE_URL = 'https://api.tomorrow.io/v4';
    protected WeatherSource $code = WeatherSource::Tomorrow;

    public function __construct()
    {
        $this->apiToken = config('weather.source.tomorrow.api_key');

        if (!$this->apiToken) {
            throw new Exception('Tomorrow: не задан токен API');
        }
    }

    public function getTitle(): string
    {
        return 'Tomorrow';
    }

    public function getWeatherByCity(string $city): WeatherDto
    {
        $response = $this->request(
            '/weather/realtime',
            [
                'apikey' => $this->apiToken,
                'location' => $city
            ]
        );

        if ($response->getStatusCode() >= 300) {
            throw new Exception('Tomorrow: ошибка API');
        }

        $data = json_decode($response->getBody()->getContents(), true)['data'] ?? [];

        if (!isset($data['values']['temperature']) || !isset($data['values']['humidity']) || !isset($data['values']['pressureSurfaceLevel'])) {
            throw new Exception('Tomorrow: недостаточно данных');
        }

        return new WeatherDto(
            (float)$data['values']['temperature'],
            (int)$data['values']['humidity'],
            (float)$data['values']['pressureSurfaceLevel']
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
