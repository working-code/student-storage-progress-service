<?php

namespace App\Service;

use App\DTO\Input\LessonWrapperDTO;
use App\DTO\LessonDTO;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Manager\CourseManager;
use App\Manager\LessonManager;
use App\Manager\TaskManager;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LessonService extends BaseTaskService
{
    public function __construct(
        protected readonly TaskManager            $taskManager,
        protected readonly LessonManager          $lessonManager,
        protected readonly CourseManager          $courseManager,
        protected readonly EntityManagerInterface $em,
        protected readonly ValidatorInterface     $validator,
        private readonly TaskService              $taskService,

    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function createLessonFromLessonWrapperDTO(LessonWrapperDTO $lessonWrapperDTO): Task
    {
        $lesson = $this->createFromLessonDTO($lessonWrapperDTO->getLessonDTO());
        $tasks = $this->findByIds($lessonWrapperDTO->getTaskIds(), TaskType::Task);

        foreach ($tasks as $task) {
            $lesson->addChildren($task);
        }

        $this->taskManager->emFlush();

        return $lesson;
    }

    /**
     * @throws ValidationException
     */
    private function createFromLessonDTO(LessonDTO $lessonDTO): Task
    {
        $lesson = $this->lessonManager->create($lessonDTO->getTitle(), $lessonDTO->getContent());

        $this->checkExistErrorsValidation($lesson);

        return $lesson;
    }

    public function findLessonById(int $id): ?Task
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->findOneBy(['id' => $id, 'type' => TaskType::Lesson]);
    }

    /**
     * @throws ValidationException
     */
    public function updateLessonFromLessonWrapperDTO(Task $lesson, LessonWrapperDTO $lessonWrapperDTO): Task
    {
        $lesson = $this->updateFromLessonDTO($lesson, $lessonWrapperDTO->getLessonDTO());
        $lessonTasks = $lesson->getChildren()->filter(
            fn(Task $task) => in_array($task->getId(), $lessonWrapperDTO->getTaskIds())
        );
        $lesson->setChildren($lessonTasks);

        $taskIdsForAdd = array_diff(
            $lessonWrapperDTO->getTaskIds(),
            $lesson->getChildren()->map(static fn(Task $task) => $task->getId())->toArray()
        );

        if ($taskIdsForAdd) {
            $tasks = $this->findByIds($taskIdsForAdd, TaskType::Task);

            foreach ($tasks as $task) {
                $lesson->addChildren($task);
            }
        }

        $this->taskManager->emFlush();

        return $lesson;
    }

    /**
     * @throws ValidationException
     */
    private function updateFromLessonDTO(Task $lesson, LessonDTO $lessonDTO): Task
    {
        return $this->updateFromTaskDTO($lesson, $lessonDTO);
    }

    public function getLessonWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->getTaskWithOffset($numberPage, $countInPage, TaskType::Lesson);
    }
}
