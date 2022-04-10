<?php

namespace App\Controller;

use App\Form\ArticleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/create", name="app_dashboard_create")
     */
    public function create(): Response
    {
        $form = $this->createForm(ArticleFormType::class);

        return $this->render('dashboard/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/history", name="app_dashboard_history")
     */
    public function history(): Response
    {
        return $this->render('dashboard/history.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/subscribe", name="app_dashboard_subscribe")
     */
    public function subscribe(): Response
    {
        return $this->render('dashboard/subscribe.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/profile", name="app_dashboard_profile")
     */
    public function profile(): Response
    {
        return $this->render('dashboard/profile.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/modules", name="app_dashboard_modules")
     */
    public function modules(): Response
    {
        return $this->render('dashboard/modules.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
