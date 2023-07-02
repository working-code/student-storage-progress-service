<?php

namespace App\DTO\Input;

use App\DTO\TaskDTO;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

class TaskWrapperDTO
{
    #[Assert\NotBlank]
    #[Assert\Valid]
    #[SerializedName('task')]
    #[Groups(TaskDTO::DEFAULT)]
    private ?TaskDTO $taskDTO;

    public function getTaskDTO(): ?TaskDTO
    {
        return $this->taskDTO;
    }

    public function setTaskDTO(?TaskDTO $taskDTO): self
    {
        $this->taskDTO = $taskDTO;

        return $this;
    }
}
