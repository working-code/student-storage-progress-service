<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('api_token')]
#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'api_token__token_unq', columns: ['token'])]
#[ORM\UniqueConstraint(name: 'api_token__user_id_unq', columns: ['user_id'])]
class ApiToken
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: true)]
    private ?string $token;

    #[ORM\OneToOne(inversedBy: 'apiToken', targetEntity: User::class)]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
