<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ResponseService
 * @package App\Service
 */
class ResponseService
{
    /**
     * @param array $response
     * @return array
     */
    public function getSuccessResponse(array $response): array
    {
        $responseOk = [
            'data' => $response
        ];

        return ['response' => $responseOk, 'status_code' => Response::HTTP_OK];
    }

    /**
     * @param int $statusCode
     * @param string $message
     * @return array
     */
    public function getErrorResponse(int $statusCode, string $message): array
    {
        $errorResponse = [
            'error' => [
                'message' => $message
            ]
        ];

        return ['response' => $errorResponse, 'status_code' => $statusCode];
    }


    /**
     * @param string $message
     * @param int $statusCode
     * @param array $responseAccepted
     * @return array
     */
    public function getSuccessMessageResponse(
        string $message,
        int $statusCode = Response::HTTP_OK,
        array $responseAccepted = []
    ): array
    {
        $responseAccepted['message'] = $message;
        return ['response' => $responseAccepted, 'status_code' => $statusCode];
    }
}
