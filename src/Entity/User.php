<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use App\Security\UserRole;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity('email')]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'user__email_unq', columns: ['email'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(normalizationContext: ['groups' => [self::GROUP_SINGLE]])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
class User
{
    public const GROUP_SINGLE = 'user:get';

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups([self::GROUP_SINGLE])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    #[Groups([self::GROUP_SINGLE])]
    private string $surname;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    #[Groups([self::GROUP_SINGLE])]
    private string $name;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    #[Groups([self::GROUP_SINGLE])]
    private string $patronymic;

    #[Assert\Email]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 100, unique: true, nullable: false)]
    #[Groups([self::GROUP_SINGLE])]
    private string $email;

    #[ORM\Column(type: 'json', length: 1024, nullable: false)]
    #[Groups([self::GROUP_SINGLE])]
    private array $roles = [];

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 120, nullable: false)]
    private string $password;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups([self::GROUP_SINGLE])]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'update')]
    #[Groups([self::GROUP_SINGLE])]
    private DateTime $updatedAt;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserAchievement::class)]
    #[Groups([self::GROUP_SINGLE])]
    private Collection $achievements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TaskAssessment::class)]
    #[Groups([self::GROUP_SINGLE])]
    private Collection $taskAssessments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserSkill::class)]
    #[Groups([self::GROUP_SINGLE])]
    private Collection $userSkills;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserCourse::class)]
    #[Groups([self::GROUP_SINGLE])]
    private Collection $userCourses;

    public function __construct()
    {
        $this->achievements = new ArrayCollection();
        $this->taskAssessments = new ArrayCollection();
        $this->userSkills = new ArrayCollection();
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

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function setAchievements(Collection $achievements): self
    {
        $this->achievements = $achievements;

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

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRole::VIEW;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUserSkills(): Collection
    {
        return $this->userSkills;
    }

    public function setUserSkills(Collection $userSkills): self
    {
        $this->userSkills = $userSkills;

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
