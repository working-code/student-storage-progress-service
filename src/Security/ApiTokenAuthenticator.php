<?php

namespace App\Security;

use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JWTEncoderInterface $JWTEncoder,
        private readonly UserService         $userService,
    )
    {
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function authenticate(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor('', 'X-AUTH-TOKEN');
        $token = $extractor->extract($request);

        if ($token === null) {
            throw new CustomUserMessageAuthenticationException('No API token was provided');
        }

        return new SelfValidatingPassport(
            new UserBadge($token, function (string $token) {
                $user = $this->userService->findUserByToken($token);

                return new AuthUser(['email' => $user->getEmail(), 'roles' => $user->getRoles()]);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['message' => 'Invalid API token'], Response::HTTP_FORBIDDEN);
    }
}
