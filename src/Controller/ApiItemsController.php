<?php

namespace App\Controller;

use App\Service\DoctrineHelper;
use App\Service\ResponseService;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Service\itemService;
use App\Service\ItemValidationService;
use App\Service\ResponseHelper;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiItemsController extends AbstractFOSRestController
{
    /**
     *@Route(methods={"GET"}, path="/api/item", name="api_items")
     *
     */
    public function index(ItemRepository $itemRepository,ResponseHelper $responseHelper)
    {
        $review = $itemRepository->findAll();
        return $responseHelper->sendJsonResponse($review);
    }

    /**
     * @Route(methods={"POST"}, path="/api/item", name="api_item_new")
     *
     */
    public function createAction
    (
        Request $request,
        itemService $itemService,
        ResponseService $responseService,
        ItemValidationService $itemValidationService,
        ResponseHelper $responseHelper

    )
    {
        try {
            $data['name'] = $request->request->get('name');
            $data['description'] = $request->request->get('description');
            if(json_last_error() != JSON_ERROR_NONE) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "invalid_json_message"
                );
                return $this->view($response['response'], $response['status_code']);
            }

            if (empty(trim($data['name'])) ) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "Name is required"
                );
                return $this->view($response['response'], $response['status_code']);
            }
            $errors = $itemValidationService->validatePostItemRequest($data);
            if (0 !== count($errors)) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $errors[0]->getMessage()
                );
                return $this->view($response['response'], $response['status_code']);
            }

            $item = $itemService->createItem($data);

            return $responseHelper->sendJsonResponse($item);
        }catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );

            return $this->view($response['response'], $response['status_code']);
        }
    }

    /**
     * @Route(methods={"PUT"}, path="/api/item/{id}/edit", name="api_item_edit")
     */
    public function edit
    (
        Request $request,
        Item $item,
        ResponseService $responseService,
        ResponseHelper $responseHelper,
        DoctrineHelper $doctrineHelper
    )
    {
        try {
            $data = $request->request->get('name');
            if(isset($data) && !empty($data)) {
                $item->setName($data);
            }
            $data = $request->request->get('description');
            if(isset($data) && !empty($data)) {
                $item->setDescription($data);
            }
            $doctrineHelper->AddToDb($item);
            return $responseHelper->sendJsonResponse($item);

        } catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );
            return $this->view($response['response'], $response['status_code']);
        }

    }

    /**
     * @Route(methods={"DELETE"}, path="/api/item/{id}", name="api_item_delete")
     */
    public function delete(Item $item,ResponseService $responseService)
    {
//        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($item);
//            $entityManager->flush();
//        }
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();

            return $responseService->getSuccessMessageResponse(
                "Item Deleted Successfully",
                Response::HTTP_CREATED
            );
        } catch (\Exception $exception) {
            return $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );
        }

    }
}
