<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Form\RegistrationFormType;
use App\Repository\ArticleRepository;
use App\Service\ArticleGenerator;
use App\Service\Subscribe;
use Doctrine\ORM\EntityManagerInterface;
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
    public function subscribe(Subscribe $subscribe): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('dashboard/subscribe.html.twig', [
            'tariff' => $user->getTariff(),
            'subscribe_to' => $user->getSubscribeTo()
        ]);
    }

    /**
     * @Route("/dashboard/profile", name="app_dashboard_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Профиль успешно изменен');
        }

        return $this->render('dashboard/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser()
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
