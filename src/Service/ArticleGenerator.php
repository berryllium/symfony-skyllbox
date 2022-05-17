<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Form\Model\ArticleFormModel;
use Diplom\ArticleSubjectProviderBundle\ArticleSubjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

class ArticleGenerator
{
    private ArticleSubjectProvider $subjectProvider;
    private SluggerInterface $slugger;
    private EntityManagerInterface $em;
    private Environment $env;

    public function __construct(ArticleSubjectProvider $subjectProvider, SluggerInterface $slugger, EntityManagerInterface $em, Environment $env)
    {
        $this->subjectProvider = $subjectProvider;
        $this->slugger = $slugger;
        $this->em = $em;
        $this->env = $env;
    }

    public function generate(ArticleFormModel $model, User $user) : Article {
        $article = new Article();
        $subject = $this->subjectProvider->getSubject($model->subject);

        $body = '';
        $paragraphs = $subject->getParagraphs(rand($model->sizeFrom, $model->sizeTo));
        foreach ($paragraphs as $p) {
            if(rand(0,1)) $body .= '<h3>' .$subject->getTitle() . '</h3>';
            $body .= '<p>' . $p . '</p>';
        }

        Morpher::getInstance()->set($model->keyword0, $model->getKeywords());
        $template = $this->env->createTemplate($body);
        $body = $template->render(['keyword' => $model->keyword0]);

        $title = $model->title ?: $this->slugger->slug($subject->getName());

        $article
            ->setTitle($title)
            ->setAuthor($user)
            ->setBody($body)
        ;

        $this->em->persist($article);
        $this->em->flush();
        return $article;
    }
}