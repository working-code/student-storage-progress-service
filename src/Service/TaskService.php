<?php

namespace App\Service;

use App\DTO\TaskDTO;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Exception;

class TaskService extends BaseTaskService
{
    /**
     * @throws ValidationException
     */
    public function createTaskFromTaskDTO(TaskDTO $taskDTO): Task
    {
        $task = $this->taskManager->create($taskDTO->getTitle(), $taskDTO->getContent());

        $this->checkExistErrorsValidation($task);
        $this->taskManager->emFlush();

        return $task;
    }

    public function findTaskById(int $id): ?Task
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->findOneBy(['id' => $id, 'type' => TaskType::Task]);
    }

    /**
     * @throws ValidationException
     */
    public function updateTaskFromTaskDTO(Task $task, TaskDTO $taskDTO): Task
    {
        return $this->updateFromTaskDTO($task, $taskDTO);
    }

    public function getTaskWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->getTaskWithOffset($numberPage, $countInPage, TaskType::Task);
    }

    /**
     * @return Task[]
     *
     * @throws Exception
     */
    public function getTasksByCourse(Task $course): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->getTasksByCourse($course);
    }
}
