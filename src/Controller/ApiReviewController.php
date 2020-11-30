<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Service\DoctrineHelper;
use App\Service\ReviewHelper;
use App\Service\TokenHelper;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

class ApiReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="api_review", methods={"GET"})
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        $review = $reviewRepository->findAll();
        $serializer = SerializerBuilder::create()->build();
        $reponse = $serializer->serialize($review, 'json');
        $reponse = json_decode($reponse);
        return new JsonResponse($reponse);
    }

    /**
     * @Route("/api/review", name="api_review_new", methods={"POST"})
     */
    public function new(Request $request,ReviewHelper $reviewHelper,TokenHelper $tokenHelper): Response
    {
        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
        $review = new Review();
        $review->setUser($user);

        $reviewHelper->updateReview($request, $review);
        return new JsonResponse(['success' => 'Review Created']);

    }

    /**
     * @Route("/api/review/{id}/edit", name="api_review_edit", methods={"PUT"})
     */
    public function edit(Request $request, Review $review, ReviewHelper $reviewHelper,TokenHelper $tokenHelper): Response
    {
        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));

        if ($review->getUser() == $user)
        {
            $reviewHelper->updateReview($request , $review);
        }else{
            return new JsonResponse(['failed' => 'you can only change your own review']);
        }

        return new JsonResponse(['success' => 'Review Updated']);
    }

    /**
     * @Route("/api/review/{id}", name="api_review_show", methods={"GET"})
     */
    public function show(Review $review): Response
    {
        $serializer = SerializerBuilder::create()->build();
        $reponse = $serializer->serialize($review, 'json');
        $reponse = json_decode($reponse);
        return new JsonResponse($reponse);
    }

    /**
     * @Route("/api/review/{id}", name="api_review_delete", methods={"DELETE"})
     */
    public function delete(Review $review, DoctrineHelper $doctrineHelper): Response
    {
        $doctrineHelper->DeleteFromDb($review);
        return new JsonResponse(['Success' => 'Record Deleted']);
    }
}
