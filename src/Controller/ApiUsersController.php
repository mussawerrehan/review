<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DoctrineHelper;
use App\Service\ResponseHelper;
use App\Service\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiUsersController extends AbstractController
{
    /**
     * @Route(methods={"GET"},path="/api/users", name="api_users")
     */
    public function index(UserRepository $userRepository,ResponseHelper $responseHelper)
    {
        $users = $userRepository->findAll();
        return $responseHelper->sendJsonResponse($users);
    }
    /**
     * @Route(methods={"POST"}, path="/api/users", name="api_users_new")
     *
     */
    public function creatAction
    (
        Request $request,
        ResponseService $responseService,
        ResponseHelper $responseHelper,
        UserPasswordEncoderInterface $userPasswordEncoder,
        DoctrineHelper $doctrineHelper
    ) {
        try {
            $data['email'] = $request->request->get('email');
            $data['password'] = $request->request->get('password');
            if(json_last_error() != JSON_ERROR_NONE) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "invalid_json_message"
                );
                return $response;
            }

            if (empty(trim($data['email'])) ) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "email is required"
                );
                return $response;
            }
            if (strlen($data['password']) < 6) {
                $response = $responseService->getErrorResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    "Password length must be at least 8 characters"
                );
                return $response;
            }

            $user = new User();
            $user->setEmail($data['email']);
            $plainPassword = $data['password'];
            $encoded = $userPasswordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $doctrineHelper->AddToDb($user);
            return $responseHelper->sendJsonResponse($user);
        }catch (\Exception $exception) {
            $response = $responseService->getErrorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "server.error_message"
            );

            return $response;
        }
    }
}
