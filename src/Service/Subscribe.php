<?php

namespace App\Service;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class Subscribe
{
    private $em;
    private $tariff_roles = [
        'plus' => 'TARIFF_PLUS',
        'pro' => 'TARIFF_PRO'
    ];
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * @param User $user
     * @param string $tariff
     * @return bool
     */
    public function subscribe(User $user, string $tariff) :bool {
        if(!isset($this->tariff_roles[$tariff])) {
            return false;
        }

        $new_role = $this->tariff_roles[$tariff];

        if(!$this->security->isGranted($new_role)) {
            foreach ($this->tariff_roles as $role) {
                $user->removeRole($role);
            }

            $user->addRole($new_role);
        }

        $user->setSubscribeTo((new DateTime())->modify('+1 week'));

        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    /**
     * Метод удаляет соответствующие роли пользователя, если подписка закончилась
     * Может запускаться, к примеру, агентом раз в день
     * @param User $user
     * @return void
     */
    public function cancelSubscribe(User $user) {
        if($user->getSubscribeTo() < new DateTime()) {
            foreach ($this->tariff_roles as $role) {
                $user->removeRole($role);
            }
            $user->setSubscribeTo(null);
            $this->em->persist($user);
            $this->em->flush();
        }
    }
}