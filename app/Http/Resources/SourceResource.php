<?php

namespace App\Http\Resources;

use App\Sources\Contracts\SourceBase;
use Illuminate\Http\Resources\Json\JsonResource;

final class SourceResource extends JsonResource
{
    /** @var SourceBase $resource */
    public $resource;

    public function toArray($request): array
    {
        return [
            'title' => $this->resource->getTitle(),
            'code' => $this->resource->getCode(),
        ];
    }

}