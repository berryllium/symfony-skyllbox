<?php

namespace App\Controller;

use App\Form\AnonymousArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function try(Request $request): Response
    {
        $cookie = $request->cookies->get('anonymous_article');
        $anonymousArticle = $cookie ? unserialize($cookie): null;
        if($anonymousArticle) {
            $form = $this->createForm(AnonymousArticleFormType::class, $anonymousArticle);
        } else {
            $form = $this->createForm(AnonymousArticleFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $anonymousArticle = $form->getData();
                $response = new RedirectResponse($this->generateUrl('app_landing_try'));
                $response->headers->setCookie(new Cookie('anonymous_article', serialize($anonymousArticle)));
                return $response;
            }
        }
        return $this->render('landing/try.html.twig', [
            'form' => $form->createView(),
            'article' => $anonymousArticle,
            'text' => $anonymousArticle ? 'Статья, содерждащая тестовое слово: ' . $anonymousArticle->word : null
        ]);
    }
}
