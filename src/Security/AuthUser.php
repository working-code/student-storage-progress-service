<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $email;
    private string $password;

    /** @var string[] */
    private array $roles;

    public function __construct(array $credentials)
    {
        $this->email = $credentials['email'];
        $this->password = $credentials['password'] ?? '';
        $this->roles = array_unique(array_merge($credentials['roles'] ?? [], [UserRole::VIEW]));
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->email;
    }
}
