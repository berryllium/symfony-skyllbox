<?php

namespace App\Controller;

use Diplom\ArticleSubjectProviderBundle\ArticleSubjectProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(ArticleSubjectProvider $provider): Response
    {
        return $this->render('test/index.html.twig', [
            'article' => $provider->getSubject('guitar')->getName(),
        ]);
    }
}
