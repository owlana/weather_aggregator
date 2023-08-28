<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityWeatherRequest;
use App\Http\Resources\SourceResource;
use App\Services\Contracts\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class WeatherController extends BaseApiController
{
    public function __construct(private readonly WeatherService $weatherService)
    {
    }

    /**
     * Получение списка источников
     * @group Weather
     *
     */
    public function getSourceList(Request $request): JsonResponse
    {
        $sources = $this->weatherService->getSources();

        return $this->sendResponse(['sources' => SourceResource::collection($sources)]);
    }

    /**
     * Получение погоды из источника по городу
     * @group Weather
     *
     * @urlParam   source    string  required    Источник    Example: tomorrow
     *
     * @response 400 scenario="Wrong source" {"message": "Weather source not found"}
     * @response 422 scenario="Validation failed" {"message": "The city field is required.", "errors": {"city": ["The city field is required."]}}
     */
    public function getSourceData(string $source, CityWeatherRequest $request): JsonResponse
    {
        try {
            $weather = $this->weatherService->getWeatherBySourceAndCity($source, $request->get('city'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse(['weather' => $weather->toArray()]);
    }

    /**
     * Получение среднего показателя погоды по всем источникам
     * @group Weather
     *
     * @response 422 scenario="Validation failed" {"message": "The city field is required.", "errors": {"city": ["The city field is required."]}}
     */
    public function getAggregateData(CityWeatherRequest $request):JsonResponse
    {
        try {
            $weather = $this->weatherService->getAggregateWeatherByCity($request->get('city'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse(['weather' => $weather->toArray()]);
    }
}
