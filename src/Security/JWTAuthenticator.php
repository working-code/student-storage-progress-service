<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly JWTEncoderInterface $JWTEncoder)
    {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function authenticate(Request $request): Passport
    {
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $extractor->extract($request);

        if ($token === null) {
            throw new CustomUserMessageAuthenticationException('No JWT was provided');
        }

        $tokenData = $this->JWTEncoder->decode($token);

        if (!isset($tokenData['email'])) {
            throw new CustomUserMessageAuthenticationException('Invalid JWT');
        }

        return new SelfValidatingPassport(
            new UserBadge($tokenData['email'], static fn() => new AuthUser($tokenData))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => 'Invalid JWT Token'], Response::HTTP_UNAUTHORIZED);
    }
}
