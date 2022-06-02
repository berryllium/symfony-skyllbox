<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\Security\Core\Security;

class Rights
{
    private ?User $user;
    private ArticleRepository $articleRepository;
    private $articleCount;
    private $articlePeriod;

    public function __construct($articleCount, $articlePeriod, Security $security, ArticleRepository $articleRepository){
        $this->user = $security->getUser();
        $this->articleRepository = $articleRepository;
        $this->articleCount = $articleCount;
        $this->articlePeriod = $articlePeriod;
    }

    public function canCreateArticle() : bool {
        if(!$this->user) {
            return false;
        }
        if($this->user->getTariff() == 'pro') {
            return true;
        }
        $articlesPerHour = $this->articleRepository->getCountUserArticlesFromDate(
            $this->user->getId(),
            (new \DateTime())->modify('-'.$this->articlePeriod.' hour')
        );
        return $articlesPerHour < $this->articleCount;
    }

    public function canUseSelfModule() : bool {
        return $this->user && $this->user->getTariff() == 'pro';
    }

    public function canAddWords() : bool {
        return $this->user && ($this->user->getTariff() == 'plus' || $this->user->getTariff() == 'pro');
    }

    public function canAddWordForm() : bool {
        return $this->user && ($this->user->getTariff() == 'plus' || $this->user->getTariff() == 'pro');
    }
}