<?php

namespace App\Entity;

use App\Repository\TaskAssessmentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table(name: 'task_assessment')]
#[ORM\Entity(repositoryClass: TaskAssessmentRepository::class)]
#[ORM\Index(columns: ['user_id'], name: 'task_assessment__user_id__ind')]
#[ORM\Index(columns: ['task_id'], name: 'task_assessment__task_id__ind')]
class TaskAssessment
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'taskAssessments')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'taskAssessments')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: false)]
    private Task $task;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private int $assessment;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'update')]
    private DateTime $updatedAt;

    #[ORM\OneToMany(mappedBy: 'taskAssessment', targetEntity: SkillAssessment::class)]
    private Collection $skillAssessments;

    public function __construct()
    {
        $this->skillAssessments = new ArrayCollection();
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;
        return $this;
    }

    public function getAssessment(): int
    {
        return $this->assessment;
    }

    public function setAssessment(int $assessment): self
    {
        $this->assessment = $assessment;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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
}
