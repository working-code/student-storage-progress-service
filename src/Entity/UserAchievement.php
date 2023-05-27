<?php

namespace App\Entity;

use App\Repository\UserAchievementRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'user_achievement')]
#[ORM\Entity(repositoryClass: UserAchievementRepository::class)]
#[ORM\Index(columns: ['user_id'], name: 'user_achievement__user_id__ind')]
#[ORM\Index(columns: ['achievement_id'], name: 'user_achievement__achievement_id__ind')]
class UserAchievement
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'achievements')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Achievement::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'achievement_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private Achievement $achievement;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(name: 'update_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'update')]
    private DateTime $updatedAt;

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

    public function getAchievement(): Achievement
    {
        return $this->achievement;
    }

    public function setAchievement(Achievement $achievement): self
    {
        $this->achievement = $achievement;

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
}
