<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Entity\SkillAssessment;
use App\Entity\TaskAssessment;
use Doctrine\ORM\EntityManagerInterface;

class SkillAssessmentManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(Skill $skill, TaskAssessment $taskAssessment, int $skillValue): SkillAssessment
    {
        return (new SkillAssessment())
            ->setSkill($skill)
            ->setTaskAssessment($taskAssessment)
            ->setSkillValue($skillValue);
    }

    public function save(SkillAssessment $skillAssessment): SkillAssessment
    {
        $this->em->persist($skillAssessment);
        $this->em->flush();

        return $skillAssessment;
    }

    public function update(SkillAssessment $skillAssessment): SkillAssessment
    {
        $this->em->flush();

        return $skillAssessment;
    }

    public function delete(SkillAssessment $skillAssessment): void
    {
        $this->em->remove($skillAssessment);
        $this->em->flush();
    }
}
