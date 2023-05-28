<?php

namespace App\Service;

use App\Entity\User;
use App\Security\AuthUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function getHashPassword(User $user, string $password): string
    {
        $authUser = new AuthUser(['email' => $user->getEmail()]);

        return $this->passwordHasher->hashPassword($authUser, $password);
    }
}
