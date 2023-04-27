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
        return (new UserSkill())
            ->setUser($user)
            ->setSkill($skill)
            ->setValue($value);
    }

    public function save(UserSkill $userSkill): UserSkill
    {
        $this->em->persist($userSkill);
        $this->em->flush();

        return $userSkill;
    }

    public function update(UserSkill $userSkill): UserSkill
    {
        $this->em->flush();

        return $userSkill;
    }

    public function delete(UserSkill $userSkill): void
    {
        $this->em->remove($userSkill);
        $this->em->flush();
    }
}
