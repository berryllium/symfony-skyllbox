<?php

namespace App\Service;

use App\Entity\User;
use App\Event\UserSubscribedEvent;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class Subscribe
{
    private $em;
    private EventDispatcherInterface $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
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
        $this->dispatcher->dispatch(new UserSubscribedEvent($user));
        return true;
    }
}