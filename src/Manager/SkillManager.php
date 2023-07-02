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
        $skill = (new Skill())
            ->setName($name);

        $this->em->persist($skill);

        return $skill;
    }

    public function update(Skill $skill, string $name): void
    {
        $skill->setName($name);
    }

    public function delete(Skill $skill): self
    {
        $this->em->remove($skill);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
