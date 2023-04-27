<?php

namespace App\Event;

use App\Entity\Skill;
use App\Entity\User;

class DeletedTaskAssessmentEvent
{
    public function __construct(
        private readonly User  $user,
        private readonly array $skills,
    )
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Skill[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }
}
