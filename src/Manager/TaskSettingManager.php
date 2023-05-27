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
        return (new TaskSetting())
            ->setTask($task)
            ->setSkill($skill)
            ->setValuePercentage($value);
    }

    public function save(TaskSetting $taskSetting): TaskSetting
    {
        $this->em->persist($taskSetting);
        $this->em->flush();

        return $taskSetting;
    }

    public function update(TaskSetting $taskSetting): TaskSetting
    {
        $this->em->flush();

        return $taskSetting;
    }

    public function delete(TaskSetting $taskSetting): void
    {
        $this->em->remove($taskSetting);
        $this->em->flush();
    }
}
