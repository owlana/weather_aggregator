<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\WeatherSource;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class WeatherControllerTest extends TestCase
{
    public function testWeatherSourcesListValidResult()
    {
        $response = $this->get(
            route('weather.sources'),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertOk();
        $response
            ->assertJsonStructure([
                'data' => [
                    'sources' => [
                        [
                            'title',
                            'code'
                        ]
                    ]
                ]
            ]);
    }

    public function testWeatherSourceResultReturnErrorOnWrongSource()
    {
        $faker = Factory::create('ru');
        $response = $this->get(
            route('weather.source_data', ['source' => $faker->word(), 'city' => $faker->city()]),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $responseArr = json_decode($response->getContent(), true);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertArrayNotHasKey('data', $responseArr);
    }

    /**
     * @dataProvider provideSourceData
     */
    public function testWeatherSourceResultReturnValidationErrorOnEmptyCity($source)
    {
        $response = $this->get(
            route('weather.source_data', ['source' => $source]),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $responseArr = json_decode($response->getContent(), true);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertNotEmpty($responseArr['errors']);
    }

    /**
     * @dataProvider provideSourceData
     */
    public function testWeatherSourceResultReturnErrorOnWrongCity($source)
    {
        $response = $this->get(
            route('weather.source_data', ['source' => $source, 'city' => 'test_' . $source]),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $responseArr = json_decode($response->getContent(), true);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertNotEmpty($responseArr['message']);
    }

    /**
     * @dataProvider provideSourceData
     */
    public function testWeatherSourceValidResult($source)
    {
        $response = $this->get(
            route('weather.source_data', ['source' => $source, 'city' => 'Москва']),
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
        );

        $response->assertOk();
        $response
            ->assertJsonStructure([
                'data' => [
                    'weather' => [
                        'temperature',
                        'humidity',
                        'pressure'
                    ]
                ]
            ]);
    }

    public function testWeatherAggregateResultReturnErrorOnWrongCity()
    {
        $response = $this->get(
            route('weather.aggregate_data', ['city' => 'test_aggregate']),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $responseArr = json_decode($response->getContent(), true);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertNotEmpty($responseArr['message']);
    }

    public function testWeatherAggregateValidResult()
    {
        $response = $this->get(
            route('weather.aggregate_data', ['city' => 'Москва']),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        );

        $response->assertOk();
        $response
            ->assertJsonStructure([
                'data' => [
                    'weather' => [
                        'temperature',
                        'humidity',
                        'pressure'
                    ]
                ]
            ]);
    }

    public static function provideSourceData(): array
    {
        $data = [];

        foreach (WeatherSource::cases() as $case) {
            $data[] = [$case->value];
        }

        return $data;
    }
}