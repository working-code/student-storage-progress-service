<?php

namespace App\DTO;

class LessonDTO extends BaseTaskDTO
{
    public const TASKS = 'lesson_task';

    /** @var TaskDTO[] */
    private array $tasks;

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function setTasks(array $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }
}
