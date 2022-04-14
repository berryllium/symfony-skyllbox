<?php

namespace App\Service;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class Subscribe
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param string $tariff
     * @return bool
     */
    public function subscribe(User $user, string $tariff) :bool {
        $user->setTariff($tariff);
        $user->setSubscribeTo((new DateTime())->modify('+1 week'));

        $this->em->persist($user);
        $this->em->flush();
        return true;
    }
}