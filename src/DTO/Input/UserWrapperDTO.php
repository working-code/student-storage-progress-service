<?php

namespace App\DTO\Input;

use App\DTO\UserDTO;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class UserWrapperDTO
{
    #[Assert\NotNull]
    #[Assert\Valid]
    #[SerializedName('user')]
    #[Groups([UserDTO::DEFAULT])]
    private ?UserDTO $userDTO;

    public function getUserDTO(): ?UserDTO
    {
        return $this->userDTO;
    }

    public function setUserDTO(?UserDTO $userDTO): self
    {
        $this->userDTO = $userDTO;

        return $this;
    }
}
