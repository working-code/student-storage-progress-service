<?php

namespace App\DTO\Builder;

use App\DTO\TaskSettingDTO;
use App\Entity\TaskSetting;

class TaskSettingDTOBuilder
{
    public function builderFromEntity(TaskSetting $taskSetting): TaskSettingDTO
    {
        return (new TaskSettingDTO())
            ->setId($taskSetting->getId())
            ->setTaskId($taskSetting->getTask()->getId())
            ->setSkillId($taskSetting->getSkill()->getId())
            ->setSkillValue($taskSetting->getValuePercentage());
    }
}
