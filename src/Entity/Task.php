<?php

namespace App\Entity;

use App\Entity\Enums\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'task')]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank]
    private string $content;

    #[ORM\Column(type: 'smallint', nullable: false, enumType: TaskType::class)]
    #[Assert\NotBlank]
    private TaskType $type;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskAssessment::class)]
    private Collection $taskAssessments;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskSetting::class)]
    private Collection $taskSettings;

    #[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'parents')]
    #[ORM\JoinTable(name: 'parent_children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'child_id', referencedColumnName: 'id')]
    private Collection $children;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'children')]
    private Collection $parents;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: UserCourse::class)]
    private Collection $userCourses;

    public function __construct()
    {
        $this->taskAssessments = new ArrayCollection();
        $this->taskSettings = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->userCourses = new ArrayCollection();
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): TaskType
    {
        return $this->type;
    }

    public function setType(TaskType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTaskAssessments(): Collection
    {
        return $this->taskAssessments;
    }

    public function setTaskAssessments(Collection $taskAssessments): self
    {
        $this->taskAssessments = $taskAssessments;

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

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function setParents(Collection $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    public function addChildren(Task $task): self
    {
        if (!$this->children->contains($task)) {
            $this->children->add($task);
        }

        return $this;
    }

    public function deleteChildren(Task $task): self
    {
        if ($this->children->contains($task)) {
            $this->children->removeElement($task);
        }

        return $this;
    }

    public function addParent(Task $task): self
    {
        if (!$this->parents->contains($task)) {
            $this->parents->add($task);
        }

        return $this;
    }

    public function getUserCourses(): Collection
    {
        return $this->userCourses;
    }

    public function setUserCourses(Collection $userCourses): void
    {
        $this->userCourses = $userCourses;
    }
}
