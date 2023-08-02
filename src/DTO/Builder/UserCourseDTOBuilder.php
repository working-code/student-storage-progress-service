<?php

namespace App\DTO\Builder;

use App\DTO\UserCourseDTO;
use App\Entity\UserCourse;

class UserCourseDTOBuilder
{
    public function buildFromEntity(UserCourse $userCourse): UserCourseDTO
    {
        return (new UserCourseDTO())
            ->setId($userCourse->getId())
            ->setUserId($userCourse->getUser()->getId())
            ->setCourseId($userCourse->getCourse()->getId());
    }
}
