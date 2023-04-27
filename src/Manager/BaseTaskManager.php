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

    public function save(Task $task): Task
    {
        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function update(Task $task): Task
    {
        $this->em->flush();

        return $task;
    }

    public function delete(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }

    protected function createTask(string $title, string $content, TaskType $type): Task
    {
        return (new Task())
            ->setTitle($title)
            ->setContent($content)
            ->setType($type);
    }
}
