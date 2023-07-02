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
        $skillAssessment = (new SkillAssessment())
            ->setSkill($skill)
            ->setTaskAssessment($taskAssessment)
            ->setSkillValue($skillValue);

        $this->em->persist($skillAssessment);

        return $skillAssessment;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
