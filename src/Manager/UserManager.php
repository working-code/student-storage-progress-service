<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(
        string $surname,
        string $name,
        string $patronymic,
        string $email,
        array  $roles,
        string $password,
    ): User
    {
        $user = (new User())
            ->setSurname($surname)
            ->setName($name)
            ->setPatronymic($patronymic)
            ->setEmail($email)
            ->setRoles($roles)
            ->setPassword($password);

        $this->em->persist($user);

        return $user;
    }

    public function update(
        User   $user,
        string $surname,
        string $name,
        string $patronymic,
        string $email,
        array  $roles,
        string $password,
    ): void
    {
        $user
            ->setSurname($surname)
            ->setName($name)
            ->setPatronymic($patronymic)
            ->setEmail($email)
            ->setRoles($roles)
            ->setPassword($password);
    }

    public function delete(User $user): self
    {
        $this->em->remove($user);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
