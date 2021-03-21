<?php

namespace App\Controller;

use App\Entity\WishlistFriend;
use App\Form\WishlistFriendType;
use App\Repository\WishlistFriendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wishlist/friend")
 */
class WishlistFriendController extends AbstractController
{
    /**
     * @Route("/", name="wishlist_friend_index", methods={"GET"})
     */
    public function index(WishlistFriendRepository $wishlistFriendRepository): Response
    {
        return $this->render('wishlist_friend/index.html.twig', [
            'wishlist_friends' => $wishlistFriendRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="wishlist_friend_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $wishlistFriend = new WishlistFriend();
        $form = $this->createForm(WishlistFriendType::class, $wishlistFriend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wishlistFriend);
            $entityManager->flush();

            return $this->redirectToRoute('wishlist_friend_index');
        }

        return $this->render('wishlist_friend/new.html.twig', [
            'wishlist_friend' => $wishlistFriend,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wishlist_friend_show", methods={"GET"})
     */
    public function show(WishlistFriend $wishlistFriend): Response
    {
        return $this->render('wishlist_friend/show.html.twig', [
            'wishlist_friend' => $wishlistFriend,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="wishlist_friend_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WishlistFriend $wishlistFriend): Response
    {
        $form = $this->createForm(WishlistFriendType::class, $wishlistFriend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('wishlist_friend_index');
        }

        return $this->render('wishlist_friend/edit.html.twig', [
            'wishlist_friend' => $wishlistFriend,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wishlist_friend_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WishlistFriend $wishlistFriend): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wishlistFriend->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wishlistFriend);
            $entityManager->flush();
        }

        return $this->redirectToRoute('wishlist_friend_index');
    }
}
