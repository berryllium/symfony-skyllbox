<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Subscribe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{
    /**
     * @Route("/subscribe/{tariff}", name="app_subscribe")
     */
    public function index($tariff, Subscribe $subscribe, MailerInterface $mailer): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if(!$subscribe->subscribe($user, $tariff)) {
            $this->addFlash('error', 'Не удалось оформить подписку');
        } else {
            $email =  (new TemplatedEmail())
                ->from(new Address('noreply@symfony.generator', 'Generator'))
                ->to(new Address($user->getEmail(), $user->getName()))
                ->subject('Подписка оформлена')
                ->htmlTemplate('email/new_subsribe_email.html.twig')
                ->context(['user' => $user]);
            $mailer->send($email);
        }

        return $this->redirectToRoute('app_dashboard_subscribe');
    }
}
