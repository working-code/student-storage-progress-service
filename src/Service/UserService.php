<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findUserById(int $userId): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);

        return $userRepository->find($userId);
    }
}
