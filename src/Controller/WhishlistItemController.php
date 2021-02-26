<?php

namespace App\Controller;

use App\Entity\WhishlistItem;
use App\Form\WhishlistItemType;
use App\Repository\WhishlistItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/whishlist/item")
 */
class WhishlistItemController extends AbstractController
{
    /**
     * @Route("/", name="whishlist_item_index", methods={"GET"})
     */
    public function index(WhishlistItemRepository $whishlistItemRepository): Response
    {
        return $this->render('whishlist_item/index.html.twig', [
            'whishlist_items' => $whishlistItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="whishlist_item_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $whishlistItem = new WhishlistItem();
        $form = $this->createForm(WhishlistItemType::class, $whishlistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($whishlistItem);
            $entityManager->flush();

            return $this->redirectToRoute('whishlist_item_index');
        }

        return $this->render('whishlist_item/new.html.twig', [
            'whishlist_item' => $whishlistItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="whishlist_item_show", methods={"GET"})
     */
    public function show(WhishlistItem $whishlistItem): Response
    {
        return $this->render('whishlist_item/show.html.twig', [
            'whishlist_item' => $whishlistItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="whishlist_item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WhishlistItem $whishlistItem): Response
    {
        $form = $this->createForm(WhishlistItemType::class, $whishlistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('whishlist_item_index');
        }

        return $this->render('whishlist_item/edit.html.twig', [
            'whishlist_item' => $whishlistItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="whishlist_item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WhishlistItem $whishlistItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$whishlistItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($whishlistItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('whishlist_item_index');
    }
}
