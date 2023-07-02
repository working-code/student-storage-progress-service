<?php

namespace App\Manager;

use App\Entity\Enums\TaskType;
use App\Entity\Task;

class LessonManager extends BaseTaskManager
{
    public function create(string $title, string $content): Task
    {
        return $this->createTask($title, $content, TaskType::Lesson);
    }
}
