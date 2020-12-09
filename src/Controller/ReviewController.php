<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Service\DoctrineHelper;
use App\Service\ReviewHelper;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/review")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="review_index", methods={"GET"})
     */
    public function index(ReviewHelper $reviewHelper): Response
    {
        if(!$this->getUser())
            return $this->redirectToRoute('app_login');
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPERADMIN'))
        {
            $reviews = $reviewHelper->findAll($this->getUser());
        }else
        {
            $reviews = $reviewHelper->findForUser($this->getUser());
        }
        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * @Route("/new", name="review_new", methods={"GET","POST"})
     */
    public function new(Request $request, DoctrineHelper $doctrineHelper): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $doctrineHelper->AddToDb($review);
            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/new.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="review_show", methods={"GET"})
     */
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="review_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Review $review): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/edit.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/status", name="update_status", methods={"PUT"})
     */
    public function updateStatus(Review $review,DoctrineHelper $doctrineHelper)
    {
        if($review->getStatus())
        {
            $review->setStatus(0);
        }
        else
        {
            $review->setStatus(1);
        }
        $doctrineHelper->AddToDb($review);
        return new JsonResponse(['success' => 'Review Updated']);
    }

    /**
     * @Route("/{id}", name="review_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Review $review, DoctrineHelper $doctrineHelper): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $doctrineHelper->DeleteFromDb($review);
        }
        return $this->redirectToRoute('review_index');
    }
}
