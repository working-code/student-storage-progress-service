<?php

namespace App\DTO\Builder;

use App\DTO\LessonDTO;
use App\Entity\Task;

class LessonDTOBuilder
{
    public function __construct(
        private readonly TaskDTOBuilder $taskDTOBuilder,
    )
    {
    }

    public function buildFromEntity(Task $lesson): LessonDTO
    {
        return (new LessonDTO())
            ->setId($lesson->getId())
            ->setTitle($lesson->getTitle())
            ->setContent($lesson->getContent())
            ->setTasks(array_map(
                fn(Task $task) => $this->taskDTOBuilder->buildFromEntity($task),
                $lesson->getChildren()->toArray()
            ));
    }
}
