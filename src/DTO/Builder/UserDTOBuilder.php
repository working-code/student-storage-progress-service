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

    public function buildFromArray(array $data): UserDTO
    {
        $userDto = new UserDTO();

        if (isset($data['id'])) {
            $userDto->setId($data['id']);
        }
        if (isset($data['surname'])) {
            $userDto->setSurname($data['surname']);
        }
        if (isset($data['name'])) {
            $userDto->setName($data['name']);
        }
        if (isset($data['patronymic'])) {
            $userDto->setPatronymic($data['patronymic']);
        }
        if (isset($data['email'])) {
            $userDto->setEmail($data['email']);
        }
        if (isset($data['roles'])) {
            $userDto->setRoles($data['roles']);
        }
        if (isset($data['password'])) {
            $userDto->setPassword($data['password']);
        }

        return $userDto;
    }
}
