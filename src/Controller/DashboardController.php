<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Service\ArticleGenerator;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(Security $security): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/create", name="app_dashboard_create")
     */
    public function create(Request $request, ArticleGenerator $generator): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $generator->generate($form->getData(), $this->getUser());
        }

        return $this->render('dashboard/create.html.twig', [
            'form' => $form->createView(),
            'article' => $article ?? null
        ]);
    }

    /**
     * @Route("/dashboard/history", name="app_dashboard_history")
     */
    public function history(ArticleRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $pageSize = 5;

        $pagination = $paginator->paginate(
            $repository->getUserArticles($user->getId()),
            $request->get('page', 1),
            $pageSize
        );

        return $this->render('dashboard/history.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/dashboard/subscribe", name="app_dashboard_subscribe")
     */
    public function subscribe(Security $security): Response
    {
        $tariff = null;
        if($security->isGranted('TARIFF_PRO')) {
            $tariff = 'pro';
        } elseif($security->isGranted('TARIFF_PLUS')) {
            $tariff = 'plus';
        }
        return $this->render('dashboard/subscribe.html.twig', [
            'tariff' => $tariff,
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

    /**
     * @Route("/dashboard/article/{slug}", name="app_dashboard_article")
     */
    public function article(Article $article): Response
    {
        return $this->render('dashboard/article.html.twig', [
            'article' => $article,
        ]);
    }
}
