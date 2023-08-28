<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Query parameters
 * @queryParam   city    string  required    Название города    Example: Moscow
 */
final class CityWeatherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city' => 'required|string'
        ];
    }
}
