<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserSubscribedEvent extends Event
{
    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}