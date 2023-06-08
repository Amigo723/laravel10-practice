<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseHelper
{
    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function response(
        bool $apiStatus = true,
        string $apiCode = '',
        string $apiMessage = '',
        array $apiData = []
    ): JsonResponse {
        $response['status'] = $apiStatus;
        $response['response_code'] = $apiCode;

        if (!empty($apiData)) {
            $response['data'] = $apiData;
        }
        if ($apiMessage) {
            $response['message'] = $this->helpers->isJson($apiMessage) ? json_decode($apiMessage) : $apiMessage;
        }
        return response()->json($response, $apiCode, []);
    }

    public function validataionFail(string $message)
    {
        return $this->response(
            false,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $message
        );
    }
}
