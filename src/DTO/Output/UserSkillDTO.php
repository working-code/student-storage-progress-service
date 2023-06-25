<?php

namespace App\DTO\Output;

use App\DTO\SkillDTO;
use App\DTO\UserDTO;
use Symfony\Component\Serializer\Annotation\Groups;

class UserSkillDTO
{
    public const SKILL_WITH_VALUE = 'skill_with_value';

    private int $id;

    private UserDTO $user;

    #[Groups(self::SKILL_WITH_VALUE)]
    private SkillDTO $skill;

    #[Groups(self::SKILL_WITH_VALUE)]
    private int $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): UserDTO
    {
        return $this->user;
    }

    public function setUser(UserDTO $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSkill(): SkillDTO
    {
        return $this->skill;
    }

    public function setSkill(SkillDTO $skill): self
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
