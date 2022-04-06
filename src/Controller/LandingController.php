<?php

namespace App\Controller;

use App\Entity\AnonymousArticle;
use App\Form\AnonymousArticleFormType;
use App\Repository\AnonymousArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

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
    public function try(AnonymousArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $anonymousArticle = new AnonymousArticle();
        if($uuid = $request->cookies->get('user_id')) {
            $anonymousArticle = $articleRepository->findOneBy(['user_id' => $uuid]);
            $form = $this->createForm(AnonymousArticleFormType::class, $anonymousArticle);
        } else {
            $form = $this->createForm(AnonymousArticleFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $anonymousArticle = $form->getData();
                $uuid = Uuid::v4()->toRfc4122();
                $anonymousArticle
                    ->setUserId($uuid);
                $entityManager->persist($anonymousArticle);
                $entityManager->flush();
                $cookie = new Cookie('user_id', $uuid);
                $response = new RedirectResponse($this->generateUrl('app_landing_try'));
                $response->headers->setCookie($cookie);
                return $response;
            }
        }
        return $this->render('landing/try.html.twig', [
            'form' => $form->createView(),
            'article' => $anonymousArticle->getId() ? $anonymousArticle : null,
            'text' => $anonymousArticle->getId() ?
                'Обработанный текст, содерждащий тестовое слово: ' . $anonymousArticle->getWord() : null
        ]);
    }
}
