<?php

namespace App\Service;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface as MailerComponent;
use Symfony\Component\Mime\Address;

class Mailer
{
    private $from;
    private MailerComponent $mailer;

    public function __construct(MailerComponent $mailer) {
        $this->from = new Address('noreply@symfony.generator', 'Generator');
        $this->mailer = $mailer;
    }
    public function send(Address $to, $subj, $template, $context = null) {
        $email =  (new TemplatedEmail())
            ->from($this->from)
            ->to($to)
            ->subject($subj)
            ->htmlTemplate($template)
            ;
        if($context) {
            $email->context($context);
        }
        $this->mailer->send($email);
    }

    public function sendNewSubscribe($user) {
        $this->send(
            new Address($user->getEmail(),
                $user->getName()),
            'Подписка оформлена',
            'email/new_subsribe_email.html.twig',
            ['user' => $user])
        ;
    }
}