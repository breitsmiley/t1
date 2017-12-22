<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;


class JsonResponseHelper
{
    /**
     * @param JsonResponse|null $response
     * @return JsonResponse
     */
    public function prepareJsonResponse(JsonResponse $response = null): JsonResponse
    {
        $response = $response ?? new JsonResponse();
        $response->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

        return $response;
    }
}
