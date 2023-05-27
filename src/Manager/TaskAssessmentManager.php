<?php

namespace App\Manager;

use App\Entity\Task;
use App\Entity\TaskAssessment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TaskAssessmentManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(Task $task, int $assessment, User $user): TaskAssessment
    {
        return (new TaskAssessment())
            ->setUser($user)
            ->setTask($task)
            ->setAssessment($assessment);
    }

    public function save(TaskAssessment $taskAssessment): TaskAssessment
    {
        $this->em->persist($taskAssessment);
        $this->em->flush();

        return $taskAssessment;
    }

    public function update(TaskAssessment $taskAssessment): TaskAssessment
    {
        $this->em->flush();

        return $taskAssessment;
    }

    public function delete(TaskAssessment $taskAssessment): void
    {
        $this->em->remove($taskAssessment);
        $this->em->flush();
    }
}
