<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Subscribe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{
    /**
     * @Route("/subscribe/{tariff}", name="app_subscribe")
     */
    public function index($tariff, Subscribe $subscribe): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if(!$subscribe->subscribe($user, $tariff)) {
            $this->addFlash('error', 'Не удалось оформить подписку');
        }
        return $this->redirectToRoute('app_dashboard_subscribe');
    }
}
