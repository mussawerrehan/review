<?php

namespace App\Controller;

use App\Service\ResponseService;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Service\itemService;
use App\Service\ItemValidationService;
use App\Service\ResponseHelper;
use App\Service\TokenHelper;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        ItemValidationService $itemValidationService

    ): View
    {
        try {
//            dd($request->request);
            $data['name'] = $request->request->get('name');
            $data['description'] = $request->request->get('description');
//            dd($data);
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

            $itemService->createItem($data);

            $response = $responseService->getSuccessMessageResponse(
                "Item Created Successfully",
                Response::HTTP_CREATED
            );
            return $this->view($response['response'], $response['status_code']);
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
    public function edit(Request $request, Item $item,ResponseHelper $responseHelper)
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
    public function delete(Request $request, Item $item)
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
