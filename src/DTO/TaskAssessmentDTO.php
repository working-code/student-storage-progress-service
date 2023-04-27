<?php

namespace App\DTO;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class TaskAssessmentDTO
{
    public const DEFAULT = 'assessment';

    private ?int $id;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?int $userId;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?int $taskId;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private ?int $assessment;

    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

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

    public function getAssessment(): ?int
    {
        return $this->assessment;
    }

    public function setAssessment(?int $assessment): self
    {
        $this->assessment = $assessment;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
