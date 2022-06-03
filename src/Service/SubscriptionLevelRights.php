<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\Security\Core\Security;

class SubscriptionLevelRights
{
    private ?User $user;
    private ArticleRepository $articleRepository;
    private $articleCount;
    private $articlePeriod;
    private $tariff;
    
    const TARIFF_PRO = 'pro';
    const TARIFF_PLUS = 'plus';

    public function __construct($articleCount, $articlePeriod, Security $security, ArticleRepository $articleRepository){
        $this->user = $security->getUser();
        if($this->user) {
            $this->tariff = $this->user->getTariff();
        } else {
            $this->tariff = null;
        }

        $this->articleRepository = $articleRepository;
        $this->articleCount = $articleCount;
        $this->articlePeriod = $articlePeriod;
    }

    public function canCreateArticle() : bool {
        if(!$this->tariff) {
            return false;
        }
        if($this->tariff == self::TARIFF_PRO) {
            return true;
        }
        $articlesPerHour = $this->articleRepository->getCountUserArticlesFromDate(
            $this->user->getId(),
            (new \DateTime())->modify('-'.$this->articlePeriod.' hour')
        );
        return $articlesPerHour < $this->articleCount;
    }

    public function canUseSelfModule() : bool {
        return $this->tariff == self::TARIFF_PRO;
    }

    public function canAddWords() : bool {
        return $this->tariff == self::TARIFF_PLUS || $this->tariff == self::TARIFF_PRO;
    }

    public function canAddWordForm() : bool {
        return $this->tariff == self::TARIFF_PLUS || $this->tariff == self::TARIFF_PRO;
    }
}