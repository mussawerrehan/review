<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();

        $star = $request->request->get('star');
        $description = $request->request->get('description');

        $review = new Review();
        $review->setStar($star);
        $review->setDescription($description);
        $review->setUser($request->getUser());

        $em->persist($review);
        $em->flush();

        return new JsonResponse(['success' => 'Review Created']);

    }

    /**
     * @Route("/api/review/{id}/edit", name="api_review_edit", methods={"PUT"})
     */
    public function edit(Request $request, Review $review): Response
    {
        $em = $this->getDoctrine()->getManager();

        $star = $request->request->get('title');
        $description = $request->request->get('description');

        $review->setStar($star);
        $review->setDescription($description);
        $review->setUser($request->getUser());

        $em->persist($review);
        $em->flush();

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
    public function delete(Review $review): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($review);
        $entityManager->flush();

        return new JsonResponse(['Success' => 'Record Deleted']);
    }
}
