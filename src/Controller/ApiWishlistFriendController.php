<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
