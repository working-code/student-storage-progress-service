<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseTaskDTO
{
    public const DEFAULT = 'task';

    private ?int $id;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?string $title;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?string $content;

    private array $parents;
    private array $children;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
