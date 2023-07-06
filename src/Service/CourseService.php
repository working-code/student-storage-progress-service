<?php

namespace App\Service;

use App\DTO\CourseDTO;
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
        $course = $this->createFromCourseDTO($courseDTOWrapper->getCourseDTO());
        $lessons = $this->findByIds($courseDTOWrapper->getLessonIds(), TaskType::Lesson);

        foreach ($lessons as $lesson) {
            $course->addChildren($lesson);
        }

        $this->taskManager->emFlush();

        return $course;
    }

    /**
     * @throws ValidationException
     */
    private function createFromCourseDTO(CourseDTO $courseDTO): Task
    {
        $course = $this->courseManager->create($courseDTO->getTitle(), $courseDTO->getContent());

        $this->checkExistErrorsValidation($course);

        return $course;
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
        $course = $this->updateFromCourseDTO($course, $courseDTOWrapper->getCourseDTO());
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

        $this->taskManager->emFlush();

        return $course;
    }

    /**
     * @throws ValidationException
     */
    private function updateFromCourseDTO(Task $course, CourseDTO $courseDTO): Task
    {
        return $this->updateFromTaskDTO($course, $courseDTO);
    }

    public function getCourseWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->em->getRepository(Task::class);

        return $taskRepository->getTaskWithOffset($numberPage, $countInPage, TaskType::Course);
    }
}
