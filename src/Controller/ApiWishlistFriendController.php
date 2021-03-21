<?php

namespace App\Controller;

use App\Entity\WishlistFriend;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use App\Service\DoctrineHelper;
use App\Service\ResponseHelper;
use App\Service\ResponseService;
use App\Service\TokenHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiWishlistFriendController extends AbstractController
{
    /**
     * @Route("/api/wishlist/friend", name="api_wishlist_friend")
     */
    public function index(): Response
    {
        return $this->render('api_wishlist_friend/index.html.twig', [
            'controller_name' => 'ApiWishlistFriendController',
        ]);
    }
    /**
     * @Route(methods={"POST"}, path="/api/wishlist/friend", name="api_wishlist_friend")
     */
    public function createAction(
        Request $request,
        ResponseService $responseService,
        ResponseHelper $responseHelper,
        DoctrineHelper $doctrineHelper,
        TokenHelper $tokenHelper,
        FriendRepository $friendRepository,
        UserRepository $userRepository,
        WishlistRepository $wishlistRepository
    ) {
        try {
            $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
            $data['friend_id'] = $request->request->get('friend_id');
            $data['wishlist_id'] = $request->request->get('wishlist_id');
            if (json_last_error() != JSON_ERROR_NONE) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "invalid_json_message"
                );
                return $response;
            }

            if (empty(trim($data['friend_id'])) || empty(trim($data['wishlist_id']))) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "wishlist_id and friend_id is required"
                );
                return $response;
            }
            $friend = $friendRepository->findOneBy(['second_user_id' => $data['friend_id'], 'type' => 'Friend']);
            if (!$friend) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend does not exist"
                );
                return $response;
            }
            $friendUserObject = $userRepository->find($data['friend_id']);
            $wishlist = $wishlistRepository->find($data['wishlist_id']);
            $wishlistFriend = new WishlistFriend();
            $wishlistFriend->setFriend($friendUserObject);
            $wishlistFriend->setWishlist($wishlist);
            $doctrineHelper->AddToDb($wishlistFriend);
        } catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );

            return $response;
        }
    }
}
