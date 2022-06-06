<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\Api\RequestHandler;
use App\Service\ArticleGenerator;
use App\Service\SubscriptionLevelRights;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/api/article/create", name="api_article_create", methods={"POST"})
     */
    public function index(Request $request, ArticleGenerator $generator, SubscriptionLevelRights $rights, RequestHandler $handler): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            if(!$rights->canCreateArticle()) {
                return $this->json([
                    'error' => 'Превышен лимит создания статей, чтобы снять лимит улучшите подписку!',
                ], Response::HTTP_OK);
            }

            $model = $handler->prepareArticleFormModel($request);
            $article = $generator->generate($model, $user);

            return $this->json([
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'content' => $article->getBody(),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
