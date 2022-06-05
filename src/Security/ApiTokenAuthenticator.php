<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private ApiTokenRepository $apiTokenRepository;
    private LoggerInterface $apiLogger;

    public function __construct(ApiTokenRepository $apiTokenRepository, LoggerInterface $apiLogger)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->apiLogger = $apiLogger;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $apiToken = str_replace('Bearer ', '', $request->headers->get('Authorization'));
        if(!$apiToken) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $token = $this->apiTokenRepository->findOneBy(['token' => $apiToken]);
        if(!$token) {
            throw new CustomUserMessageAuthenticationException('Token ' . $apiToken . ' not found');
        }

        /** @var User $user */
        $user = $token->getUser();
        if(!$user || $token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token not found');
        }

        return new SelfValidatingPassport(new UserBadge($user->getEmail()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        $this->apiLogger->info('Авторизация пользователя api', [
            'login' => $token->getUserIdentifier(),
            'token' => str_replace('Bearer ', '', $request->headers->get('Authorization')),
            'target_path' => $this->getTargetPath($request->getSession(), $firewallName),
            'url' => $request->getRequestUri()
        ]);

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => $exception->getMessage()], 401);
    }

}
