<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;


abstract class BaseApiController extends Controller
{
    /**
     * @param array $data
     * @param int $code
     *
     * @return JsonResponse
     */
    public function sendResponse(
        array $data,
        int $code = \Symfony\Component\HttpFoundation\Response::HTTP_OK
    ): JsonResponse {
        return Response::json(
            [
                'data' => $data,
            ],
            $code
        );
    }

    /**
     * @param string $message
     * @param string[] $errors
     * @param int $code
     *
     * @return JsonResponse
     */
    public function sendError(
        string $message,
        array $errors = [],
        int $code = \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return Response::json(
            [
                'message' => $message,
                'errors' => $errors
            ],
            $code
        );
    }

}