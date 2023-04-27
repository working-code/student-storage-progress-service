<?php

namespace App\DTO\Input;

use App\DTO\TaskAssessmentDTO;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class TaskAssessmentWrapperDTO
{
    #[SerializedName('assessment')]
    #[Groups(TaskAssessmentDTO::DEFAULT)]
    #[Assert\NotNull]
    #[Assert\Valid]
    private ?TaskAssessmentDTO $taskAssessmentDTO;

    public function getTaskAssessmentDTO(): ?TaskAssessmentDTO
    {
        return $this->taskAssessmentDTO;
    }

    public function setTaskAssessmentDTO(?TaskAssessmentDTO $taskAssessmentDTO): self
    {
        $this->taskAssessmentDTO = $taskAssessmentDTO;

        return $this;
    }
}
