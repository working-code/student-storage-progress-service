<?php

namespace App\Entity;

use App\Repository\UserSkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'user_skill')]
#[ORM\Entity(repositoryClass: UserSkillRepository::class)]
#[ORM\Index(columns: ['user_id'], name: 'user_skill__user_id__ind')]
#[ORM\Index(columns: ['skill_id'], name: 'user_skill__skill_id__ind')]
#[ORM\UniqueConstraint(name: 'user_skill__user_id_skill_id__un_ind', columns: ['user_id', 'skill_id'])]
class UserSkill
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userSkills')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[Assert\NotBlank]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: 'userSkills')]
    #[ORM\JoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    #[Assert\NotBlank]
    private Skill $skill;

    #[ORM\Column(name: 'value', type: 'integer', nullable: false)]
    #[Assert\NotNull]
    private int $value;

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

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
