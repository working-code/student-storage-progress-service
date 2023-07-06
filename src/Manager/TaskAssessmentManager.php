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
        $taskAssessment = (new TaskAssessment())
            ->setUser($user)
            ->setTask($task)
            ->setAssessment($assessment);

        $this->em->persist($taskAssessment);

        return $taskAssessment;
    }

    //public function update(
    //    TaskAssessment $taskAssessment,
    //    Task           $task,
    //    int            $assessment,
    //    User           $user
    //): TaskAssessment
    //{
    //    return $taskAssessment
    //        ->setTask($task)
    //        ->setAssessment($assessment)
    //        ->setUser($user);
    //}

    public function delete(TaskAssessment $taskAssessment): self
    {
        $this->em->remove($taskAssessment);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
