<?php

namespace App\Service;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

class TokenGenerator
{
    private $em;
    private ApiTokenRepository $apiTokenRepository;

    public function __construct(EntityManagerInterface $em, ApiTokenRepository $apiTokenRepository)
    {
        $this->em = $em;
        $this->apiTokenRepository = $apiTokenRepository;
    }

    public function generate(User $user) {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $date = (new \DateTime())->modify('+1 week');
        $apiToken = $this->apiTokenRepository->findOneBy(['user' => $user]);
        if(!$apiToken) {
            $apiToken = (new ApiToken())->setUser($user);
        }
        $apiToken
            ->setToken($token)
            ->setExpiresAt($date);
        $this->em->persist($apiToken);
        $this->em->flush();
    }
}