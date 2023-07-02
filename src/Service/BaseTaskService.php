<?php

namespace App\Service;

use App\DTO\BaseTaskDTO;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Manager\CourseManager;
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
        protected readonly CourseManager          $courseManager,
        protected readonly EntityManagerInterface $em,
        protected readonly ValidatorInterface     $validator,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    protected function updateFromTaskDTO(Task $task, BaseTaskDTO $taskDTO, bool $save = true): Task
    {
        $this->taskManager->update($task, $taskDTO->getTitle(), $taskDTO->getContent());

        $this->checkExistErrorsValidation($task);

        if ($save) {
            $this->taskManager->emFlush();
        }

        return $task;
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

    protected function findByIds(array $ids, TaskType $taskType): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->findBy(['id' => $ids, 'type' => $taskType]);
    }
}
