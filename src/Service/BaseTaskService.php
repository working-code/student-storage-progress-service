<?php

namespace App\Service;

use App\DTO\BaseTaskDTO;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Manager\LessonManager;
use App\Manager\TaskManager;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseTaskService
{
    public function __construct(
        protected readonly TaskManager            $taskManager,
        protected readonly LessonManager          $lessonManager,
        private readonly ValidatorInterface       $validator,
        protected readonly EntityManagerInterface $em,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function updateFromTaskDTO(Task $task, BaseTaskDTO $taskDTO, bool $save = true): Task
    {
        $task->setTitle($taskDTO->getTitle())
            ->setContent($taskDTO->getContent());

        $this->checkExistErrorsValidation($task);

        return $save ? $this->taskManager->update($task) : $task;
    }

    /**
     * @throws ValidationException
     */
    protected function checkExistErrorsValidation(Task $task): void
    {
        $errors = $this->validator->validate($task);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @throws ValidationException
     */
    protected function createFromTaskDTO(BaseTaskDTO $taskDTO, TaskType $taskType, bool $save = true): Task
    {
        $task = $this->taskManager->create($taskDTO->getTitle(), $taskDTO->getContent());
        $task->setType($taskType);

        $this->checkExistErrorsValidation($task);

        return $save ? $this->taskManager->save($task) : $task;
    }

    protected function findByIds(array $ids, TaskType $taskType): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->findBy(['id' => $ids, 'type' => $taskType]);
    }
}
