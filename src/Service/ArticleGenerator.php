<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Form\Model\ArticleFormModel;
use Diplom\ArticleSubjectProviderBundle\ArticleSubjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleGenerator
{
    private ArticleSubjectProvider $subjectProvider;
    private SluggerInterface $slugger;
    private EntityManagerInterface $em;

    public function __construct(ArticleSubjectProvider $subjectProvider, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $this->subjectProvider = $subjectProvider;
        $this->slugger = $slugger;
        $this->em = $em;
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

        $replacement = [];

        preg_match_all('#\{\{(\s*[^}]+\s*)\}\}#', $body, $matches);
        foreach ($matches[1] as $k => $match) {
            $placeholder = explode('|', trim($match));
            $variable = $placeholder[0];
            $filter = $placeholder[1];
            if($variable == 'keyword') {
                $property = $variable;
                preg_match('#morph\((.+)\)#', $filter, $i);
                $i = trim($i[1]) ?: 0;
                $property = $property . $i;
                $value = $model->$property;
            }
            $replacement[$matches[0][$k]] = $value;
        }
        $body = strtr($body, $replacement);

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