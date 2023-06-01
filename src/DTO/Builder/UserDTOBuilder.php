<?php

namespace App\DTO\Builder;

use App\DTO\UserDTO;
use App\Entity\User;

class UserDTOBuilder
{
    public function buildFromEntity(User $user): UserDTO
    {
        return (new UserDTO())
            ->setId($user->getId())
            ->setSurname($user->getSurname())
            ->setName($user->getName())
            ->setPatronymic($user->getPatronymic())
            ->setEmail($user->getEmail())
            ->setCreatedAt($user->getCreatedAt()->format('Y.m.d H:i:s'))
            ->setUpdatedAt($user->getUpdatedAt()->format('Y.m.d H:i:s'))
            ->setRoles($user->getRoles());
    }
}
