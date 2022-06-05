<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_dashboard');
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route ("/generate_token", name="app_generate_token")
     */
    public function generateToken(EntityManagerInterface $em, ApiTokenRepository $apiTokenRepository) {
        /** @var User $user */
        $user = $this->getUser();
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $date = (new \DateTime())->modify('+1 week');
        $apiToken = $apiTokenRepository->findOneBy(['user' => $user]);
        if(!$apiToken) {
            $apiToken = (new ApiToken())->setUser($user);
        }
        $apiToken
            ->setToken($token)
            ->setExpiresAt($date);
        $em->persist($apiToken);
        $em->flush();
        return $this->redirectToRoute('app_dashboard_profile');
    }
}