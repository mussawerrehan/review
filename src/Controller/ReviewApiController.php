<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewApiController extends AbstractController
{
    /**
     * @Route("/review/api", name="review_api")
     */
    public function index(): Response
    {
        return $this->render('review_api/index.html.twig', [
            'controller_name' => 'ReviewApiController',
        ]);
    }
}
