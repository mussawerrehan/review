<?php


namespace App\Service;


use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseHelper
{
    public function sendJsonResponse($data) {
        $serializer = SerializerBuilder::create()->build();
        $response = $serializer->serialize($data, 'json');
        $response = json_decode($response);
        return new JsonResponse([
            'success' => '200',
            'data' => $response
        ]);
    }
}
