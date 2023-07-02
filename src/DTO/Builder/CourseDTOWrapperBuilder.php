<?php

namespace App\DTO\Builder;

use App\DTO\Input\CourseDTOWrapper;

class CourseDTOWrapperBuilder
{
    public function __construct(private readonly CourseDTOBuilder $courseDTOBuilder)
    {
    }

    public function buildFromArray(array $data): CourseDTOWrapper
    {
        $courseDTOWrapper = new CourseDTOWrapper();

        if (isset($data['course'])) {
            $courseDTOWrapper->setCourseDTO($this->courseDTOBuilder->buildFromArray($data['course']));
        }
        if (isset($data['lessonIds'])) {
            $courseDTOWrapper->setLessonIds($data['lessonIds']);
        }

        return $courseDTOWrapper;
    }
}
