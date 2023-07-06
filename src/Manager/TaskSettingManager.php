<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\TaskSetting;
use Doctrine\ORM\EntityManagerInterface;

class TaskSettingManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(Task $task, Skill $skill, int $value): TaskSetting
    {
        $taskSetting = (new TaskSetting())
            ->setTask($task)
            ->setSkill($skill)
            ->setValuePercentage($value);

        $this->em->persist($taskSetting);

        return $taskSetting;
    }

    public function update(TaskSetting $taskSetting, Task $task, Skill $skill, int $value): void
    {
        $taskSetting
            ->setTask($task)
            ->setSkill($skill)
            ->setValuePercentage($value);
    }

    public function delete(TaskSetting $taskSetting): self
    {
        $this->em->remove($taskSetting);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
