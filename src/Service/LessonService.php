<?php

namespace App\Service;

use App\DTO\Input\LessonWrapperDTO;
use App\DTO\LessonDTO;
use App\DTO\TaskDTO;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Repository\TaskRepository;

class LessonService extends BaseTaskService
{
    /**
     * @throws ValidationException
     */
    public function createLessonWithTasksFromLessonDTO(LessonDTO $lessonDTO): Task
    {
        $lesson = $this->createFromTaskDTO($lessonDTO, TaskType::Lesson, false);

        foreach ($lessonDTO->getTasks() as $taskDTO) {
            $task = $this->createFromTaskDTO($taskDTO, TaskType::Task);
            $lesson->addChildren($task);
        }

        return $this->lessonManager->save($lesson);
    }

    /**
     * @throws ValidationException
     */
    public function updateLessonWithTasksByLessonDTO(Task $lesson, LessonDTO $lessonDTO): Task
    {
        $lesson = $this->updateFromTaskDTO($lesson, $lessonDTO, false);
        $tasks = array_combine(
            array_map(static fn(TaskDTO $taskDTO) => $taskDTO->getId(), $lessonDTO->getTasks()),
            $lessonDTO->getTasks()
        );

        foreach ($lesson->getChildren() as $task) {
            /** @var Task $task */
            $taskDTO = $tasks[$task->getId()];
            $task->setTitle($taskDTO->getTitle())
                ->setContent($taskDTO->getContent());
        }

        return $this->lessonManager->update($lesson);
    }

    public function deleteLessonWithTasks(Task $lesson): void
    {
        foreach ($lesson->getChildren() as $task) {
            $lesson->deleteChildren($task);
            $this->taskManager->delete($task);
        }

        $this->lessonManager->delete($lesson);
    }

    /**
     * @throws ValidationException
     */
    public function createLessonFromLessonWrapperDTO(LessonWrapperDTO $lessonWrapperDTO): Task
    {
        $lesson = $this->createFromTaskDTO($lessonWrapperDTO->getLessonDTO(), TaskType::Lesson, false);
        $tasks = $this->findByIds($lessonWrapperDTO->getTaskIds(), TaskType::Task);

        foreach ($tasks as $task) {
            $lesson->addChildren($task);
        }

        return $this->taskManager->save($lesson);
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
        $lesson = $this->updateFromTaskDTO($lesson, $lessonWrapperDTO->getLessonDTO(), false);
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

        return $this->taskManager->update($lesson);
    }

    public function getLessonWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->getTaskWithOffset($numberPage, $countInPage, TaskType::Lesson);
    }
}
