<?php

namespace App\Service;

use App\Entity\User;
use App\Security\AuthUser;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTEncoderInterface         $JWTEncoder,
        private readonly int                         $tokenTTL,
    )
    {
    }

    public function getHashPassword(User $user, string $password): string
    {
        $authUser = new AuthUser(['email' => $user->getEmail()]);

        return $this->passwordHasher->hashPassword($authUser, $password);
    }

    /**
     * @throws JWTEncodeFailureException
     */
    public function getJWTByUserDTO(User $user): string
    {
        return $this->JWTEncoder->encode([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'exp' => time() + $this->tokenTTL,
        ]);
    }

    public function isCredentialValid(User $user, string $password): bool
    {
        $authUser = new AuthUser(['email' => $user->getEmail(), 'password' => $user->getPassword()]);

        return $this->passwordHasher->isPasswordValid($authUser, $password);
    }
}
