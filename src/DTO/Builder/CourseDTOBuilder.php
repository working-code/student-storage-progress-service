<?php

namespace App\DTO\Builder;

use App\DTO\CourseDTO;
use App\DTO\LessonDTO;
use App\Entity\Task;

class CourseDTOBuilder
{
    public function buildFromEntity(Task $course): CourseDTO
    {
        return (new CourseDTO())
            ->setId($course->getId())
            ->setTitle($course->getTitle())
            ->setContent($course->getContent())
            ->setLessons(array_map(
                fn(Task $lesson) => (new LessonDTO())
                    ->setId($lesson->getId())
                    ->setTitle($lesson->getTitle())
                    ->setContent($lesson->getContent()),
                $course->getChildren()->toArray()
            ));
    }

    public function buildFromArray(array $data): CourseDTO
    {
        $courseDTO = new CourseDTO();

        if (isset($data['title'])) {
            $courseDTO->setTitle($data['title']);
        }
        if (isset($data['content'])) {
            $courseDTO->setContent($data['content']);
        }

        return $courseDTO;
    }
}
