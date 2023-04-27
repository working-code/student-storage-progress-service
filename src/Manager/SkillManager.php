<?php

namespace App\Manager;

use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;

class SkillManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(string $name): Skill
    {
        return (new Skill())
            ->setName($name);
    }

    public function save(Skill $skill): Skill
    {
        $this->em->persist($skill);
        $this->em->flush();

        return $skill;
    }

    public function update(Skill $skill): Skill
    {
        $this->em->flush();

        return $skill;
    }

    public function delete(Skill $skill): void
    {
        $this->em->remove($skill);
        $this->em->flush();
    }
}
