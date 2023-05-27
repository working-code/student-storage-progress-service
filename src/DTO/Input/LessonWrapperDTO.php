<?php

namespace App\DTO\Input;

use App\DTO\LessonDTO;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class LessonWrapperDTO
{
    #[Assert\NotNull]
    #[Assert\Valid]
    #[SerializedName('lesson')]
    #[Groups(LessonDTO::DEFAULT)]
    private ?LessonDTO $lessonDTO;

    #[Assert\NotBlank]
    #[Groups(LessonDTO::TASKS)]
    private ?array $taskIds;

    public function getLessonDTO(): ?LessonDTO
    {
        return $this->lessonDTO;
    }

    public function setLessonDTO(?LessonDTO $lessonDTO): self
    {
        $this->lessonDTO = $lessonDTO;

        return $this;
    }

    public function getTaskIds(): ?array
    {
        return $this->taskIds;
    }

    public function setTaskIds(?array $taskIds): self
    {
        $this->taskIds = $taskIds;

        return $this;
    }
}
