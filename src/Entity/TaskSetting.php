<?php

namespace App\Entity;

use App\Repository\TaskSettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'task_setting')]
#[ORM\Entity(repositoryClass: TaskSettingRepository::class)]
#[ORM\Index(columns: ['skill_id'], name: 'task_setting__skill_id__ind')]
#[ORM\Index(columns: ['task_id'], name: 'task_setting__task_id__ind')]
class TaskSetting
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'taskSettings')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    private ?Task $task = null;

    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: 'taskSettings')]
    #[ORM\JoinColumn(name: 'skill_id')]
    private ?Skill $skill = null;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private ?int $valuePercentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getValuePercentage(): ?int
    {
        return $this->valuePercentage;
    }

    public function setValuePercentage(?int $valuePercentage): self
    {
        $this->valuePercentage = $valuePercentage;
        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;
        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;
        return $this;
    }
}
