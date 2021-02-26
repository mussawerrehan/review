<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Service\TokenHelper;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiItemsController extends AbstractController
{
    /**
     * @Route("/api/items", name="api_items")
     */
    public function index(ItemRepository $itemRepository): Response
    {
        $review = $itemRepository->findAll();
        $serializer = SerializerBuilder::create()->build();
        $reponse = $serializer->serialize($review, 'json');
        $reponse = json_decode($reponse);
        return new JsonResponse($reponse);
    }

    /**
     * @Route("/api/item", name="api_item_new", methods={"POST"})
     */
    public function new(Request $request,ItemRepository $itemRepository,TokenHelper $tokenHelper): Response
    {
        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
        $item = new Item();
        $item->setUser($user);

        return new JsonResponse(['success' => 'Review Created']);

    }

//    /**
//     * @Route("/api/item/{id}", name="api_item_edit", methods={"PUT"})
//     */
//    public function edit(Request $request,TokenHelper $tokenHelper): Response
//    {
////        $user = $tokenHelper->getUserFromToken($request->headers->get('Authorization'));
//
////        if ($review->getUser() == $user)
////        {
////            $reviewHelper->updateReview($request , $review);
////        }else{
////            return new JsonResponse(['failed' => 'you can only change your own review']);
////        }
//
//        return new JsonResponse(['success' => 'Review Updated']);
//    }

//    /**
//     * @Route("/api/item/{id}", name="api_item_show", methods={"GET"})
//     */
//    public function show(Review $review): Response
//    {
//        $serializer = SerializerBuilder::create()->build();
//        $reponse = $serializer->serialize($review, 'json');
//        $reponse = json_decode($reponse);
//        return new JsonResponse($reponse);
//    }
//
//    /**
//     * @Route("/api/item/{id}", name="api_item_delete", methods={"DELETE"})
//     */
//    public function delete(
//        Review $review,
//        Request $request,
//    ReviewHelper $reviewHelper
//    ): Response
//    {
//        return $reviewHelper->delete($review,$request->headers->get('Authorization'));
//    }
}
