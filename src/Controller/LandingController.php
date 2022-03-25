<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    /**
     * @Route("/", name="app_landing_index")
     */
    public function index(): Response
    {
        return $this->render('landing/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/try", name="app_landing_try")
     */
    public function try(): Response
    {
        return $this->render('landing/try.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
