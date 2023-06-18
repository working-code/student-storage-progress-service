<?php

namespace App\DTO\Builder;

use App\DTO\Output\RecalculateSkillsForUserDTO;
use App\Entity\Skill;
use App\Entity\User;

class RecalculateSkillsForUserDTOBuilder
{
    /**
     * @param Skill[] $skills
     */
    public function buildFromUserAndSkills(User $user, array $skills): RecalculateSkillsForUserDTO
    {
        return (new RecalculateSkillsForUserDTO())
            ->setUserId($user->getId())
            ->setSkillIds(array_map(static fn(Skill $skill) => $skill->getId(), $skills));
    }
}
