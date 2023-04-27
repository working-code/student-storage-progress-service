<?php

namespace App\DTO\Builder;

use App\DTO\SkillDTO;
use App\Entity\Skill;

class SkillDTOBuilder
{
    public function buildFromEntity(Skill $skill): SkillDTO
    {
        return (new SkillDTO())
            ->setId($skill->getId())
            ->setName($skill->getName());
    }
}
