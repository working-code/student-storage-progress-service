<?php

namespace App\Manager;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseTaskManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function update(Task $task, string $title, string $content): void
    {
        $task
            ->setTitle($title)
            ->setContent($content);
    }

    public function delete(Task $task): self
    {
        $this->em->remove($task);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }

    protected function createTask(string $title, string $content, TaskType $type): Task
    {
        $task = (new Task())
            ->setTitle($title)
            ->setContent($content)
            ->setType($type);

        $this->em->persist($task);

        return $task;
    }
}
