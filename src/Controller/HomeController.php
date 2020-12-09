<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ProductRepository $productRepository): Response
    {
//        dd($productRepository->findWithAverage());
        return $this->render('home/productIndex.html.twig', [
            'products' =>  $productRepository->findWithAverage(),
        ]);
    }
}
