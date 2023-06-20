<?php

namespace App\DTO\Builder;

use App\DTO\Output\UserSkillDTO;
use App\Entity\UserSkill;

class UserSkillDTOBuilder
{
    public function __construct(
        private readonly UserDTOBuilder  $userDTOBuilder,
        private readonly SkillDTOBuilder $skillDTOBuilder,
    )
    {
    }

    public function buildFromEntity(UserSkill $userSkill): UserSkillDTO
    {
        return (new UserSkillDTO())
            ->setId($userSkill->getId())
            ->setUser($this->userDTOBuilder->buildFromEntity($userSkill->getUser()))
            ->setSkill($this->skillDTOBuilder->buildFromEntity($userSkill->getSkill()))
            ->setValue($userSkill->getValue());
    }
}
