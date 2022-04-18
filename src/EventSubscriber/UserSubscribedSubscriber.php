<?php

namespace App\EventSubscriber;

use App\Event\UserSubscribedEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscribedSubscriber implements EventSubscriberInterface
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }

    public function onUserSubscribe(UserSubscribedEvent $event)
    {
        $this->mailer->sendNewSubscribe($event->getUser());
    }

    public static function getSubscribedEvents()
    {
        return [
            UserSubscribedEvent::class => 'onUserSubscribe'
        ];
    }
}
