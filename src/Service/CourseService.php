<?php

namespace App\Service;

use App\DTO\Input\CourseDTOWrapper;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Repository\TaskRepository;

class CourseService extends BaseTaskService
{
    /**
     * @throws ValidationException
     */
    public function createCourseFromCourseWrapperDTO(CourseDTOWrapper $courseDTOWrapper): Task
    {
        $course = $this->createFromTaskDTO($courseDTOWrapper->getCourseDTO(), TaskType::Course, false);
        $lessons = $this->findByIds($courseDTOWrapper->getLessonIds(), TaskType::Lesson);

        foreach ($lessons as $lesson) {
            $course->addChildren($lesson);
        }

        return $this->taskManager->save($course);
    }

    public function findCourseById(int $id): ?Task
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->findOneBy(['id' => $id, 'type' => TaskType::Course]);
    }

    /**
     * @throws ValidationException
     */
    public function updateCourseFromCourseWrapperDTO(Task $course, CourseDTOWrapper $courseDTOWrapper): Task
    {
        $course = $this->updateFromTaskDTO($course, $courseDTOWrapper->getCourseDTO(), false);
        $courseLessons = $course->getChildren()->filter(
            fn(Task $course) => in_array($course->getId(), $courseDTOWrapper->getLessonIds())
        );
        $course->setChildren($courseLessons);

        $lessonIdsForAdd = array_diff(
            $courseDTOWrapper->getLessonIds(),
            $course->getChildren()->map(static fn(Task $lesson) => $lesson->getId())->toArray()
        );

        if ($lessonIdsForAdd) {
            $lessons = $this->findByIds($lessonIdsForAdd, TaskType::Lesson);

            foreach ($lessons as $lesson) {
                $course->addChildren($lesson);
            }
        }

        return $this->taskManager->save($course);
    }

    public function getCourseWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->getTaskWithOffset($numberPage, $countInPage, TaskType::Course);
    }
}
