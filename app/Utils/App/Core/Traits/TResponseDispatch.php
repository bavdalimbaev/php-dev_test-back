<?php

namespace App\Utils\App\Core\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait TResponseDispatch
{
    private $apiResponse = null;
    private $statusCode = Response::HTTP_OK;
    private $message = 'OK';

    public function setResponse($data = null, $message = null, $statusCode = Response::HTTP_OK)
    {
        $this->message = !isset($message)
            ? Response::$statusTexts[$statusCode]
            : $message;

        $this->statusCode = $statusCode;

        if (!is_null($data))
            $this->apiResponse = $data;

        return $this;
    }


    public function createErrorResponse($message = 'Something went wrong, contact to administration', $data = null, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $data
            ? response()->json([ 'message' => $message, 'data' => $data, ], $statusCode)
            : response()->json([ 'message' => $message, ], $statusCode);
    }


    public function create404Error($message = 'Item not found', $statusCode = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json([ 'message' => $message, ], $statusCode);
    }


    public function createResponse(): JsonResponse
    {
        if (is_null($this->apiResponse))
            $this->apiResponse['message'] = Response::$statusTexts[Response::HTTP_OK];

        return response()->json([ 'data' => $this->apiResponse ], $this->statusCode);
    }
}
