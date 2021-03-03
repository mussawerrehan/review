<?php


namespace App\Service;
use App\Repository\ItemRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

class itemService
{
    private $item;
    private $responseHelper;

    public function __construct(ItemRepository $itemRepository,ResponseHelper $responseHelper)
    {
        $this->item = $itemRepository;
        $this->responseHelper = $responseHelper;
    }

    public function create($item) {
        return $this->responseHelper->sendJsonResponse($item);
    }

}
