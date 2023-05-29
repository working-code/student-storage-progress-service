<?php

namespace App\Manager;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(string $token, User $user): ApiToken
    {
        return (new ApiToken())
            ->setToken($token)
            ->setUser($user);
    }

    public function save(ApiToken $apiToken): ApiToken
    {
        $this->em->persist($apiToken);

        return $this->update($apiToken);
    }

    public function update(ApiToken $apiToken): ApiToken
    {
        $this->em->flush();

        return $apiToken;
    }

    public function delete(ApiToken $apiToken): void
    {
        $this->em->remove($apiToken);
        $this->em->flush();
    }
}
