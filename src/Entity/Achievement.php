<?php

namespace App\Entity;

use App\Repository\AchievementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'achievement')]
#[ORM\Entity(repositoryClass: AchievementRepository::class)]
class Achievement
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $description;

    #[ORM\OneToMany(mappedBy: 'achievement', targetEntity: UserAchievement::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): self
    {
        $this->users = $users;
        return $this;
    }
}
