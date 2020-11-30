<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        if(!$this->getUser())
            return $this->redirectToRoute('app_login');
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPERADMIN'))
        {
            $reviws = $reviewRepository->findAll();
        }else
        {
            $reviws = $reviewRepository->findBy(['status' => 1]);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'reviews' => $reviws,
        ]);
    }
}
