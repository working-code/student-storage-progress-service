<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class ExceptionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 2]
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof AccessDeniedException) {
            $event->setResponse(new JsonResponse(['description' => 'Access denied'], Response::HTTP_FORBIDDEN));
        }

        if ($exception instanceof AuthenticationCredentialsNotFoundException) {
            $event->setResponse(new JsonResponse(['description' => 'Access denied'], Response::HTTP_UNAUTHORIZED));
        }

        if ($exception instanceof JWTDecodeFailureException) {
            $event->setResponse(new JsonResponse(['description' => 'Invalid JWT Token'], Response::HTTP_UNAUTHORIZED));
        }
    }
}
