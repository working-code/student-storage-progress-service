<?php

namespace App\Entity;

use App\Repository\SkillAssessmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'skill_assessment')]
#[ORM\Entity(repositoryClass: SkillAssessmentRepository::class)]
#[ORM\Index(columns: ['skill_id'], name: 'skill_assessment__skill_id__ind')]
#[ORM\Index(columns: ['task_assessment_id'], name: 'skill_assessment__task_assessment_id__ind')]
class SkillAssessment
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: 'skillAssessments')]
    #[ORM\JoinColumn(name: 'skill_id', referencedColumnName: 'id', nullable: false)]
    private Skill $skill;

    #[ORM\ManyToOne(targetEntity: TaskAssessment::class, inversedBy: 'skillAssessments')]
    #[ORM\JoinColumn(name: 'task_assessment_id', referencedColumnName: 'id', nullable: false)]
    private TaskAssessment $taskAssessment;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private int $skillValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): self
    {
        $this->skill = $skill;
        return $this;
    }

    public function getTaskAssessment(): TaskAssessment
    {
        return $this->taskAssessment;
    }

    public function setTaskAssessment(TaskAssessment $taskAssessment): self
    {
        $this->taskAssessment = $taskAssessment;
        return $this;
    }

    public function getSkillValue(): int
    {
        return $this->skillValue;
    }

    public function setSkillValue(int $skillValue): self
    {
        $this->skillValue = $skillValue;
        return $this;
    }
}
