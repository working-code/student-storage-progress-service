<?php

namespace App\DTO\Input;

use App\DTO\TaskSettingDTO;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class TaskSettingWrapperDTO
{
    #[SerializedName('taskSetting')]
    #[Groups(TaskSettingDTO::DEFAULT)]
    #[Assert\NotNull]
    #[Assert\Valid]
    private ?TaskSettingDTO $taskSettingDTO;

    public function getTaskSettingDTO(): ?TaskSettingDTO
    {
        return $this->taskSettingDTO;
    }

    public function setTaskSettingDTO(?TaskSettingDTO $taskSettingDTO): self
    {
        $this->taskSettingDTO = $taskSettingDTO;

        return $this;
    }
}
