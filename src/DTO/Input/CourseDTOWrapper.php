<?php

namespace App\DTO\Input;

use App\DTO\CourseDTO;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class CourseDTOWrapper
{
    #[Assert\NotNull]
    #[Assert\Valid]
    #[SerializedName('course')]
    #[Groups(CourseDTO::DEFAULT)]
    private ?CourseDTO $courseDTO;

    #[Assert\NotBlank]
    #[Groups(CourseDTO::LESSONS)]
    private ?array $lessonIds;

    public function getCourseDTO(): ?CourseDTO
    {
        return $this->courseDTO;
    }

    public function setCourseDTO(?CourseDTO $courseDTO): self
    {
        $this->courseDTO = $courseDTO;

        return $this;
    }

    public function getLessonIds(): ?array
    {
        return $this->lessonIds;
    }

    public function setLessonIds(?array $lessonIds): self
    {
        $this->lessonIds = $lessonIds;

        return $this;
    }
}
