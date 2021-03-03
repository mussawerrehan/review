<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Service\itemService;
use App\Service\ResponseHelper;
use App\Service\TokenHelper;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use function MongoDB\BSON\toJSON;

class ApiItemsController extends AbstractController
{
    /**
     *@Route(methods={"GET"}, path="/api/item", name="api_items")
     *
     */
    public function index(ItemRepository $itemRepository,ResponseHelper $responseHelper): Response
    {
        $review = $itemRepository->findAll();
        return $responseHelper->sendJsonResponse($review);
//        $serializer = SerializerBuilder::create()->build();
//        $reponse = $serializer->serialize($review, 'json');
//        $reponse = json_decode($reponse);
//        return new JsonResponse($reponse);
    }

    /**
     * @Route(methods={"POST"}, path="/api/item", name="api_item_new")
     *
     */
    public function new(Request $request,ResponseHelper $responseHelper,itemService $itemService): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $responseHelper->sendJsonResponse($item);
        }
        return new JsonResponse([
            'Error' => $form->getErrors()
        ]);
    }

    /**
     * @Route(methods={"PUT"}, path="/api/item/{id}/edit", name="api_item_edit")
     */
    public function edit(Request $request, Item $item,ResponseHelper $responseHelper): Response
    {
        $form = $this->createForm(ItemType::class, $item);
//        $form->handleRequest($request);
//        $form->submit($form);
        $form->submit($request->request->all());
        if ( $form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $responseHelper->sendJsonResponse($item);
        }
        return new JsonResponse([
            'Error' => $form->getErrors()
        ]);
    }

    /**
     * @Route(methods={"DELETE"}, path="/api/item/{id}", name="api_item_delete")
     */
    public function delete(Request $request, Item $item): Response
    {
//        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($item);
//            $entityManager->flush();
//        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();

        return new JsonResponse([
            'success' => 'Item Deleted'
        ]);
    }
}
