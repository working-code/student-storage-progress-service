<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class SkillDTO
{
    public const DEFAULT = 'skill';

    #[Groups(self::DEFAULT)]
    private ?int $id;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
