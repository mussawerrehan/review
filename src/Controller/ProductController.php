<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/produst", name="produst")
     */
    public function index(): Response
    {
        return $this->render('produst/index.html.twig', [
            'controller_name' => 'ProdustController',
        ]);
    }
}
