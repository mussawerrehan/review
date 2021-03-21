<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use App\Service\DoctrineHelper;
use App\Service\ResponseHelper;
use App\Service\ResponseService;
use App\Service\TokenHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiFriendsController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, path="/api/friends", name="api_friends")
     */
    public function index
    (
        TokenHelper $tokenHelper,
        Request $request,
        UserRepository $userRepository,
        FriendRepository $friendRepository,
        ResponseHelper $responseHelper
    ): Response
    {
        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
        $friends = $friendRepository->findBy(['first_user_id' => $user]);
        return $responseHelper->sendJsonResponse($friends);
    }
    /**
     * @Route(methods={"POST"}, path="/api/friends/request", name="api_friends_new")
     */
    public function sendRequest
    (
        Request $request,
        ResponseService $responseService,
        ResponseHelper $responseHelper,
        DoctrineHelper $doctrineHelper,
        TokenHelper $tokenHelper,
        UserRepository $userRepository,
        FriendRepository $friendRepository
    )
    {
        try {
            $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
            $data['friend_id'] = $request->request->get('friend_id');
            if (json_last_error() != JSON_ERROR_NONE) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "invalid_json_message"
                );
                return $response;
            }

            if (empty(trim($data['friend_id']))) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend_id is required"
                );
                return $response;
            }
            $secondUser = $userRepository->findOneBy(['id' => $data['friend_id']]);
            if (!$secondUser) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend does not exist"
                );
                return $response;
            }
            $friendRequest = $friendRepository->findOneBy(['second_user_id' => $data['friend_id']]);
            if (!$friendRequest) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend request not found"
                );
                return $response;
            }
            $friend = new Friend();
            $friend->setFirstUserId($user);
            $friend->setSecondUserId($secondUser);
            $friend->setType('pending');
            $doctrineHelper->AddToDb($friend);
            return $responseHelper->sendJsonResponse($friend);
        } catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );

            return $response;
        }
    }
    /**
     * @Route(methods={"POST"}, path="/api/friends/accept", name="api_friends_accept")
     */
    public function accept
    (
        Request $request,
        ResponseService $responseService,
        ResponseHelper $responseHelper,
        DoctrineHelper $doctrineHelper,
        TokenHelper $tokenHelper,
        FriendRepository $friendRepository,
        UserRepository $userRepository
    ) {
        try {
            $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
            $data['friend_id'] = $request->request->get('friend_id');
            if (json_last_error() != JSON_ERROR_NONE) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "invalid_json_message"
                );
                return $response;
            }

            if (empty(trim($data['friend_id']))) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend_id is required"
                );
                return $response;
            }
            $secondUser = $userRepository->findOneBy(['id' => $data['friend_id']]);
            if (!$secondUser) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend does not exist"
                );
                return $response;
            }
            $friendRequest = $friendRepository->findOneBy(['first_user_id' => $data['friend_id']]);
            if (!$friendRequest) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend request not found"
                );
                return $response;
            }
            if($friendRequest->getType() == 'Friend')
            {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "friend request already accepted"
                );
                return $response;
            }
            $friend = new Friend();
            $friend->setFirstUserId($user);
            $friend->setSecondUserId($secondUser);
            $friend->setType('Friend');
            $friendRequest->setType('Friend');
            $doctrineHelper->AddToDb($friend);
            $doctrineHelper->AddToDb($friendRequest);
            return $responseHelper->sendJsonResponse($friend);
        } catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );

            return $response;
        }
    }
    /**
     * @Route(methods={"GET"}, path="/api/friends/request", name="api_friends_request_get")
     */
    public function getRequests
    (
        TokenHelper $tokenHelper,
        FriendRepository $friendRepository,
        Request $request,
        ResponseHelper $responseHelper
    ) {
        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
        $friendRequests = $friendRepository->findBy(['second_user_id' => $user->getId()]);
        return $responseHelper->sendJsonResponse($friendRequests);
    }

}
