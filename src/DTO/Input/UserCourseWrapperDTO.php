<?php

namespace App\DTO\Input;

use App\DTO\UserCourseDTO;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

class UserCourseWrapperDTO
{
    #[Assert\NotNull]
    #[Assert\Valid]
    #[SerializedName('userCourse')]
    #[Groups(UserCourseDTO::DEFAULT)]
    private UserCourseDTO $userCourseDTO;

    public function getUserCourseDTO(): UserCourseDTO
    {
        return $this->userCourseDTO;
    }

    public function setUserCourseDTO(UserCourseDTO $userCourseDTO): self
    {
        $this->userCourseDTO = $userCourseDTO;

        return $this;
    }
}
