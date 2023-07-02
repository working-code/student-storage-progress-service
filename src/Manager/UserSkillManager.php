<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Entity\User;
use App\Entity\UserSkill;
use Doctrine\ORM\EntityManagerInterface;

class UserSkillManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(User $user, Skill $skill, int $value): UserSkill
    {
        $userSkill = (new UserSkill())
            ->setUser($user)
            ->setSkill($skill)
            ->setValue($value);

        $this->em->persist($userSkill);

        return $userSkill;
    }

    public function delete(UserSkill $userSkill): self
    {
        $this->em->remove($userSkill);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
