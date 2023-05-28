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
        return (new User())
            ->setSurname($surname)
            ->setName($name)
            ->setPatronymic($patronymic)
            ->setEmail($email)
            ->setRoles($roles)
            ->setPassword($password);
    }

    public function save(User $user): User
    {
        $this->em->persist($user);

        return $this->update($user);
    }

    public function update(User $user): User
    {
        $this->em->flush();

        return $user;
    }

    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
