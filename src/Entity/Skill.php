<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'skill')]
#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: SkillAssessment::class)]
    private Collection $skillAssessments;

    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: TaskSetting::class)]
    private Collection $taskSettings;

    public function __construct()
    {
        $this->skillAssessments = new ArrayCollection();
        $this->taskSettings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSkillAssessments(): Collection
    {
        return $this->skillAssessments;
    }

    public function setSkillAssessments(Collection $skillAssessments): self
    {
        $this->skillAssessments = $skillAssessments;
        return $this;
    }

    public function getTaskSettings(): Collection
    {
        return $this->taskSettings;
    }

    public function setTaskSettings(Collection $taskSettings): self
    {
        $this->taskSettings = $taskSettings;
        return $this;
    }
}
