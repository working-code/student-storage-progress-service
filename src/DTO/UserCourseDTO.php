<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserCourseDTO
{
    public const DEFAULT = 'user_course_default';

    private int $id;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private int $userId;

    #[Assert\NotBlank]
    #[Groups(self::DEFAULT)]
    private int $courseId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCourseId(): int
    {
        return $this->courseId;
    }

    public function setCourseId(int $courseId): self
    {
        $this->courseId = $courseId;

        return $this;
    }
}
