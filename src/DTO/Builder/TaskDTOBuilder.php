<?php

namespace App\DTO\Builder;

use App\DTO\TaskDTO;
use App\Entity\Task;

class TaskDTOBuilder
{
    public function buildFromEntity(Task $task): TaskDTO
    {
        return (new TaskDTO())
            ->setId($task->getId())
            ->setTitle($task->getTitle())
            ->setContent($task->getContent());
    }
}
