<?php


namespace App\Service;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

class itemService
{
    private $item;
    private $responseHelper;
    private $doctrineHelper;

    public function __construct
    (
        ItemRepository $itemRepository,
        ResponseHelper $responseHelper,
        DoctrineHelper $doctrineHelper
    ) {
        $this->item = $itemRepository;
        $this->responseHelper = $responseHelper;
        $this->doctrineHelper = $doctrineHelper;
    }

    public function createItem($data) {
        $item = new Item();
        $item->setDescription($data['description']);
        $item->setName($data['name']);

        $this->doctrineHelper->AddToDb($item);
        return $item;
    }

}
