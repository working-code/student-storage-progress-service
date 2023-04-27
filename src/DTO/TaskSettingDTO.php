<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class TaskSettingDTO
{
    public const DEFAULT = 'task_setting';

    private ?int $id;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?int $taskId;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?int $skillId;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?int $skillValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTaskId(): ?int
    {
        return $this->taskId;
    }

    public function setTaskId(?int $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getSkillId(): ?int
    {
        return $this->skillId;
    }

    public function setSkillId(?int $skillId): self
    {
        $this->skillId = $skillId;

        return $this;
    }

    public function getSkillValue(): ?int
    {
        return $this->skillValue;
    }

    public function setSkillValue(?int $skillValue): self
    {
        $this->skillValue = $skillValue;

        return $this;
    }
}
