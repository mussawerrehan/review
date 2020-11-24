<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="api_review")
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        $review = $reviewRepository->findAll();
        $serializer = SerializerBuilder::create()->build();
        $reponse = $serializer->serialize($review, 'json');
        $reponse = json_decode($reponse);
        return new JsonResponse($reponse);
    }
}
