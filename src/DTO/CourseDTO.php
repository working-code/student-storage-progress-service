<?php

namespace App\DTO;

class CourseDTO extends BaseTaskDTO
{
    public const LESSONS = 'course_lessons';

    /** @var LessonDTO[] */
    private array $lessons;

    public function getLessons(): array
    {
        return $this->lessons;
    }

    public function setLessons(array $lessons): self
    {
        $this->lessons = $lessons;

        return $this;
    }
}
