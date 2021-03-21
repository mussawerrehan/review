<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Repository\WishlistRepository;
use App\Service\DoctrineHelper;
use App\Service\ResponseHelper;
use App\Service\ResponseService;
use App\Service\TokenHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiWishlistController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, path="/api/wishlist", name="api_wishlists")
     */
    public function index
    (
        WishlistRepository $wishlistRepository,
        TokenHelper $tokenHelper,
        Request $request,
        ResponseHelper $responseHelper
    )
    {
        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
        $wishlists = $wishlistRepository->findBy(['user' => $user->getId()]);
        return $responseHelper->sendJsonResponse($wishlists);
    }
    /**
     * @Route(methods={"POST"}, path="/api/wishlist", name="api_wishlist")
     */
    public function createAction (
        Request $request,
        TokenHelper $tokenHelper,
        ResponseHelper $responseHelper,
        ResponseService $responseService,
        DoctrineHelper $doctrineHelper
    ) {
        try {
            $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
            $data['name'] = $request->request->get('name');

            if(json_last_error() != JSON_ERROR_NONE) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "invalid_json_message"
                );
                return $response;
            }

            if (empty(trim($data['name'])) ) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "name is required"
                );
                return $response;
            }
            $wishlist = new Wishlist();
            $wishlist->setName($data['name']);
            $wishlist->setUser($user);
            $doctrineHelper->AddToDb($wishlist);
            return $responseHelper->sendJsonResponse($wishlist);
        }catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );

            return $response;
        }
    }
    /**
     * @Route(methods={"GET"}, path="/api/wishlist/{id}", name="api_wishlists")
     */
    public function fetchOne
    (
        Wishlist $wishlist,
        ResponseHelper $responseHelper
    ) {
        return $responseHelper->sendJsonResponse($wishlist);
    }
    /**
     * @Route(methods={"DELETE"}, path="/api/wishlist/{id}", name="api_item_delete")
     */
    public function delete(Wishlist $wishlist,ResponseService $responseService)
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wishlist);
            $entityManager->flush();
            return $responseService->getSuccessMessageResponse(
                "Item Deleted Successfully",
                Response::HTTP_CREATED
            );
        }catch (\Exception $exception) {
            return $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );
        }

    }
}
